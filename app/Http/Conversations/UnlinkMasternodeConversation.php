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
use BotMan\Drivers\Telegram\Extensions\User;
use Str;

class UnlinkMasternodeConversation extends Conversation
{
    const VALUE_YES = 'yes';
    const VALUE_NO  = 'no';
    protected ?TelegramUser $user = null;
    protected string $ownerAddress = '';

    public function __construct(User $user, string $ownerAddress = '')
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
            $this->askAddress();
        } else {
            $this->askDeletion();
        }
    }

    protected function askAddress(): void
    {
        $this->ask(__('unlinkMasternodeConversation.ask_owner_address'), function (Answer $answer) {
            if (strlen($answer->getText()) !== 34) {
                return $this->repeat(__('unlinkMasternodeConversation.error.invalid_owner_address'));
            }

            $this->ownerAddress = $answer->getText();
            $this->askDeletion();
        }, array_merge([
            'parse_mode' => 'Markdown',
        ]));
    }

    public function askDeletion(): void
    {
        if (app(MasternodeService::class)->userHasAddress($this->user, $this->ownerAddress)) {
            $question = Question::create(__('unlinkMasternodeConversation.ask_deletion'))
                ->addButtons([
                    Button::create(__('unlinkMasternodeConversation.buttons.yes'))->value(self::VALUE_YES),
                    Button::create(__('unlinkMasternodeConversation.buttons.no'))->value(self::VALUE_NO),
                ]);
            $this->ask($question, function (Answer $answer) {
                if (!$answer->isInteractiveMessageReply()) {
                    $this->repeat();
                }

                if ($answer->getValue() === self::VALUE_YES) {
                    app(MasternodeService::class)->deleteMasternode(
                        $this->user,
                        $this->ownerAddress
                    );
                    $this->say(__('unlinkMasternodeConversation.final'));
                } else {
                    $this->say(__('unlinkMasternodeConversation.final_stop'));
                }
            }, array_merge([
                'parse_mode' => 'Markdown',
            ]));
        } else {
            $this->say(__('unlinkMasternodeConversation.error.unknown_address'));
        }
    }
}
