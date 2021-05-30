<?php

namespace App\Http\Conversations;

use App\Models\Service\MasternodeService;
use App\Models\Service\TelegramUserService;
use App\Models\TelegramUser;
use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Str;

class LinkMasternodeConversation extends Conversation
{
    const VALUE_YES = 'yes';
    const VALUE_NO  = 'no';
    protected ?TelegramUser $user = null;
    protected string $ownerAddress = '';
    protected ?string $name = null;

    public function __construct(UserInterface $user, string $ownerAddress = '')
    {
        $this->user = app(TelegramUserService::class)->getTelegramUser($user);
        $this->ownerAddress = $ownerAddress;
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        if (app(MasternodeService::class)->countMasternodeForUserInput($this->ownerAddress) === 0) {
            $this->askOwnerAddress();
        } else {
            $this->askName();
        }
    }

    protected function askOwnerAddress(): void
    {
        $this->ask(__('linkMasternodeConversation.ask_owner_address'), function (Answer $answer) {
            if (app(MasternodeService::class)->countMasternodeForUserInput($answer->getText()) === 0) {
                return $this->repeat(__('linkMasternodeConversation.error.invalid_owner_address'));
            }

            $this->ownerAddress = $answer->getText();
            $this->askName();
        }, array_merge([
            'parse_mode' => 'Markdown',
        ]));
    }

    protected function askName(): void
    {
        $this->ask(__('linkMasternodeConversation.ask_name'), function (Answer $answer) {
            if ($answer->getText() === 'random') {
                $this->name = Str::random(10);
            } else {
                $this->name = $answer->getText();
            }

            $this->askAlarm();
        }, array_merge([
            'parse_mode' => 'Markdown',
        ]));
    }

    protected function askAlarm(): void
    {
        $question = Question::create(__('linkMasternodeConversation.ask_alarm'))
            ->addButtons([
                Button::create(__('linkMasternodeConversation.buttons.yes'))->value(self::VALUE_YES),
                Button::create(__('linkMasternodeConversation.buttons.no'))->value(self::VALUE_NO),
            ]);
        $this->ask($question, function (Answer $answer) {
            if (!$answer->isInteractiveMessageReply()) {
                $this->repeat();
            }
            $masternodeCreated = app(MasternodeService::class)->createMasternodeForUser(
                $this->user,
                $this->ownerAddress,
                $this->name,
                $answer->getValue() === self::VALUE_YES
            );

            if ($masternodeCreated) {
                $this->say(__('linkMasternodeConversation.final'));
            } else {
                $this->say(__('linkMasternodeConversation.error.duplicated_address'));
            }
        }, array_merge([
            'parse_mode' => 'Markdown',
        ]));
    }
}
