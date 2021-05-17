<?php

namespace App\Http\Conversations;

use App\Http\Service\MNMonitorService;
use App\Models\TelegramUser;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class SyncMasternodeMonitorConversation extends Conversation
{
    private ?TelegramUser $user = null;

    public function __construct(TelegramUser $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $this->say(__('syncMasternodeMonitor.intro'), [
            'parse_mode' => 'HTML',
        ]);
        $user = $this->user;
        $this->ask(__('syncMasternodeMonitor.question'), function (Answer $answer) use ($user) {
            $masternodes = app(MNMonitorService::class)->syncMasternodesForUser($user, $answer->getText());

            if (count($masternodes) === 0) {
                $this->say(__('syncMasternodeMonitor.result.no_masternodes'));

                return;
            }
            $this->say(trans_choice('syncMasternodeMonitor.result.masternodes_synced', count($masternodes),
                ['number' => count($masternodes)]));
        });
    }
}
