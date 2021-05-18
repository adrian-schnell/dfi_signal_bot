<?php

namespace App\Http\Conversations;

use App\Http\Service\MasternodeMonitorService;
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
    const VALUE_NO = 'no';
    private ?TelegramUser $user = null;
    private string $ownerAddress;
    private string $name;

    public function __construct(string $ownerAddress, UserInterface $user)
    {
        $this->user = app(TelegramUserService::class)->getTelegramUser($user);
        $this->ownerAddress = $ownerAddress;
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        if (strlen($this->ownerAddress) !== 34) {
            $this->askOwnerAddress();
        }

        $this->ask(__('linkMasternodeConversation.ask_name'), function (Answer $answer) {
            if ($answer->getText() === '') {
                $this->name = Str::random(10);
            } else {
                $this->name = $answer->getText();
            }
            $this->askAlarm();
        }, array_merge([
            'parse_mode' => 'Markdown',
        ]));
    }

    protected function askOwnerAddress(): void
    {
        $this->ask(__('linkMasternodeConversation.ask_owner_address'), function (Answer $answer) {
            if (strlen($answer->getText()) !== 34) {
                ray('owner != 34');
                $this->repeat(__('linkMasternodeConversation.error.invalid_owner_address'));
            }
            ray('owner == 34');

            $this->ownerAddress = $answer->getText();
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
