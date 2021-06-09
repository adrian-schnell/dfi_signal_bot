<?php

namespace App\Jobs;

use App\Http\Service\DefichainApiService;
use App\Http\Service\MasternodeMonitorService;
use App\Http\Service\TelegramMessageService;
use App\Models\TelegramUser;
use App\Models\UserMasternode;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MNMonitorSyncMasternodesForUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected TelegramUser $user;

    public function __construct(TelegramUser $user)
    {
        $this->user = $user;
    }

    public function handle(TelegramMessageService $telegramMessageService): void
    {
        $countBefore = UserMasternode::where('telegramUserId', $this->user->id)
            ->where('synced_masternode_monitor', true)->count();

        $syncedMasternodes = app(MasternodeMonitorService::class)
            ->syncMasternodesForUser($this->user, $this->user->mn_monitor_sync_key);

        $countAfter = count($syncedMasternodes);

        app()->setLocale($this->user->language);
        if ($countAfter !== $countBefore) {
            app(DefichainApiService::class)->storeMintedBlockForTelegramUser($this->user);
        }
        if ($countAfter === 0) { // no masternodes stored - disable sync
            $this->user->update([
                'mn_monitor_sync_key' => null,
            ]);
            $telegramMessageService->sendMessage(
                    $this->user,
                    __('resyncMasternodeMonitor.no_masternodes'),
                    ['parse_mode' => 'Markdown']
                );
        } elseif ($countAfter > $countBefore) { // masternode added
            $difference = $countAfter - $countBefore;
            $telegramMessageService->sendMessage(
                    $this->user,
                    trans_choice(
                        'resyncMasternodeMonitor.new_masternodes_found',
                        $difference,
                        [
                            'count' => $difference
                        ]
                    ),
                    ['parse_mode' => 'Markdown']
                );
            $telegramMessageService->sendMessage(
                $this->user,
                __('resyncMasternodeMonitor.list_feature'),
                ['parse_mode' => 'Markdown']
            );
        } elseif ($countAfter < $countBefore) { // masternode removed
            $difference = $countAfter - $countBefore;
            $telegramMessageService->sendMessage(
                    $this->user,
                    trans_choice(
                        'resyncMasternodeMonitor.masternodes_removed',
                        $difference,
                        [
                            'count' => abs($difference)
                        ]
                    ),
                    ['parse_mode' => 'Markdown']
                );
            $telegramMessageService->sendMessage(
                $this->user,
                __('resyncMasternodeMonitor.list_feature'),
                ['parse_mode' => 'Markdown']
            );
        }
    }
}
