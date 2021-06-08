<?php

namespace App\Http\Conversations;

use App\Http\Service\DefichainApiService;
use App\Http\Service\MasternodeMonitorService;
use App\Models\Service\TelegramUserService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class SyncKeyChangedConversation extends Conversation
{
    private TelegramUserService $telegramUserService;
    public string $syncKey = '';

    public function __construct()
    {
        $this->telegramUserService = app(TelegramUserService::class);
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $this->say(__('syncKeyChangedConversation.intro'), [
            'parse_mode' => 'HTML',
        ]);
        $user = $this->telegramUserService->getTelegramUser($this->getBot()->getUser());
        $this->ask(__('syncKeyChangedConversation.question'), function (Answer $answer) use ($user) {
            $this->syncKey = $answer->getText();
            $masternodes   = app(MasternodeMonitorService::class)->syncMasternodesForUser($user, $this->syncKey);

            if (count($masternodes) === 0) {
                $this->say(__('syncKeyChangedConversation.result.no_masternodes'));

                return $this->repeat(__('syncKeyChangedConversation.question'));
            }

            $this->say(__('syncKeyChangedConversation.result.updated'));
            $this->say(trans_choice('syncKeyChangedConversation.result.masternodes_synced',
                count($masternodes),
                ['number' => count($masternodes)]));
        });
    }
}
