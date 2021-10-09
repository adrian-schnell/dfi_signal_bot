<?php

namespace App\Http\Conversations;

use App\Http\Service\DefichainApiService;
use App\Http\Service\MasternodeMonitorService;
use App\Models\Service\TelegramUserService;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class SyncMasternodeMonitorConversation extends Conversation
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
        $this->say(__('syncMasternodeMonitorConversation.intro'), [
            'parse_mode' => 'HTML',
        ]);
        $user = $this->telegramUserService->getTelegramUser($this->getBot()->getUser());
        $this->ask(__('syncMasternodeMonitorConversation.question'), function (Answer $answer) use ($user) {
            $this->syncKey = $answer->getText();
            $masternodes   = app(MasternodeMonitorService::class)->syncMasternodesForUser($user, $this->syncKey);

            if (count($masternodes) === 0) {
                $this->say(__('syncMasternodeMonitorConversation.result.no_masternodes'));

                return $this->repeat(__('syncMasternodeMonitorConversation.question'));
            }

            $storeKeyQuestion = Question::create(__('syncMasternodeMonitorConversation.question_store_key'))
                ->addButtons([
                    Button::create(__('syncMasternodeMonitorConversation.buttons.yes'))->value('yes'),
                    Button::create(__('syncMasternodeMonitorConversation.buttons.no'))->value('no'),
                ]);
            $this->ask($storeKeyQuestion,
                function (Answer $answer) use ($user, $masternodes, $storeKeyQuestion) {
                    if (!$answer->isInteractiveMessageReply()) {
                        return $this->repeat($storeKeyQuestion);
                    }
                    if ($answer->getValue() === 'yes') {
                        $user->update([
                            'mn_monitor_sync_key' => $this->syncKey,
                        ]);
                    }

                    $this->say(trans_choice('syncMasternodeMonitorConversation.result.masternodes_synced',
                        count($masternodes),
                        ['number' => count($masternodes)]));
                    $this->getBot()->startConversation(new EnableMasternodeAlarmConversation($masternodes));
                    app(DefichainApiService::class)->storeMintedBlockForTelegramUser($user);
                });
        });
    }
}
