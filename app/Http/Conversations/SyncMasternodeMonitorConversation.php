<?php

namespace App\Http\Conversations;

use App\Http\Service\MasternodeMonitorService;
use App\Models\Service\TelegramUserService;
use App\Models\TelegramUser;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class SyncMasternodeMonitorConversation extends Conversation
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
        $this->say(__('syncMasternodeMonitorConversation.intro'), [
            'parse_mode' => 'HTML',
        ]);
        $user = $this->telegramUserService->getTelegramUser($this->getBot()->getUser());
        $this->ask(__('syncMasternodeMonitorConversation.question'), function (Answer $answer) use ($user) {
            $masternodes = app(MasternodeMonitorService::class)->syncMasternodesForUser($user, $answer->getText());

            if (count($masternodes) === 0) {
                $this->say(__('syncMasternodeMonitorConversation.result.no_masternodes'));

                return;
            }
            $this->say(trans_choice('syncMasternodeMonitorConversation.result.masternodes_synced', count($masternodes),
                ['number' => count($masternodes)]));
            $this->getBot()->startConversation(new EnableMasternodeAlarmConversation($masternodes));
        });
    }
}
