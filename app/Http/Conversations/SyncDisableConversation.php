<?php

namespace App\Http\Conversations;

use App\Http\Service\DefichainApiService;
use App\Http\Service\MasternodeMonitorService;
use App\Models\Service\TelegramUserService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class SyncDisableConversation extends Conversation
{
    private TelegramUserService $telegramUserService;

    public function __construct()
    {
        $this->telegramUserService = app(TelegramUserService::class);
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $user = $this->telegramUserService->getTelegramUser($this->getBot()->getUser());

        if (is_null($user->mn_monitor_sync_key)) {
            $this->say(__('syncDisableConversation.result.no_sync_enabled'), [
                'parse_mode' => 'Markdown',
            ]);

            return;
        }

        $question = Question::create(__('syncDisableConversation.intro'))->addButtons([
            Button::create(__('syncDisableConversation.buttons.yes'))->value('yes'),
            Button::create(__('syncDisableConversation.buttons.no'))->value('no'),
        ]);
        $this->ask($question, function (Answer $answer) use ($user, $question) {
            if (!$answer->isInteractiveMessageReply()) {
                return $this->repeat($question);
            }

            if ($answer->getValue() === 'yes') {
                $user->update([
                   'mn_monitor_sync_key' => null,
                ]);
                $this->say(__('syncDisableConversation.result.disabled'));
            }
        }, [
            'parse_mode' => 'Markdown',
        ]);
    }
}
