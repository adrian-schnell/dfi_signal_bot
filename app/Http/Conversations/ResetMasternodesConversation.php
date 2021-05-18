<?php

namespace App\Http\Conversations;

use App\Http\Service\MasternodeMonitorService;
use App\Models\DfiMasternode;
use App\Models\TelegramUser;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class ResetMasternodesConversation extends Conversation
{
    const VALUE_YES = 'yes';
    const VALUE_NO  = 'no';

    private TelegramUser $telegramUser;

    public function __construct(TelegramUser $telegramUser)
    {
        $this->telegramUser = $telegramUser;
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $question = Question::create('Do you really want do reset all data?')
            ->addButtons([
                Button::create(__('resetMasternodeConversation.buttons.yes'))->value(self::VALUE_YES),
                Button::create(__('resetMasternodeConversation.buttons.no'))->value(self::VALUE_NO),
            ]);
        $user = $this->telegramUser;
        $this->ask($question, function (Answer $answer) use ($user) {
            if (!$answer->isInteractiveMessageReply()) {
                $this->repeat();
                return;
            }
            if ($answer->getValue() === self::VALUE_YES) {
                app(MasternodeMonitorService::class)->resetMasternodes($user);
                $user->delete();
                $this->say(__('resetMasternodeConversation.success'));
            }
        }, array_merge([
            'parse_mode' => 'Markdown',
        ]));
    }
}
