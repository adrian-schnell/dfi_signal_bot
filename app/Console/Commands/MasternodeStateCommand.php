<?php

namespace App\Console\Commands;

use App\Enum\MNStates;
use App\Http\Service\TelegramMessageService;
use App\Models\Masternode;
use App\Models\Service\OnetimeNotificationService;
use App\Models\UserMasternode;
use Illuminate\Console\Command;

class MasternodeStateCommand extends Command
{
    protected $signature = 'masternode:state-check';
    protected $description = 'Checks if a masternode is pre-resigned or resigned and notifies user';
    protected TelegramMessageService $messageService;
    protected OnetimeNotificationService $onetimeNotificationService;

    public function handle(
        TelegramMessageService $messageService,
        OnetimeNotificationService $onetimeNotificationService
    ): void {
        $this->messageService = $messageService;
        $this->onetimeNotificationService = $onetimeNotificationService;

        $desctructiveMasternodes = Masternode::whereIn('state', MNStates::DESTRUCTIVE_STATES)
            ->with('userMasternodes')
            ->withCount('userMasternodes')
            ->having('user_masternodes_count', '>=', 1)
            ->limit(10)
            ->get();

        $desctructiveMasternodes->each(function (Masternode $masternode) {
            $masternode->userMasternodes->each(function (UserMasternode $userMasternode) {
                if ($userMasternode->isPreResigned() && !$userMasternode->hasNotificationWithName(MNStates::MN_PRE_RESIGNED)) {
                    $this->notifyPreResigned($userMasternode);
                } elseif ($userMasternode->isResigned() && !$userMasternode->hasNotificationWithName(MNStates::MN_RESIGNED)) {
                    $this->notifyResigned($userMasternode);
                }
            });
        });
    }

    protected function notifyPreResigned(UserMasternode $userMasternode): void
    {
        set_language($userMasternode->user->language);
        $success = $this->messageService->sendMessage(
            $userMasternode->user,
            __('mn_state_notification.pre_resigned',
                [
                    'name'          => $userMasternode->name,
                    'resignedBlock' => $userMasternode->masternode->resign_height + 2016,
                ]),
            ['parse_mode' => 'Markdown']
        );
        if ($success) {
            $this->onetimeNotificationService->notificationSendForModel($userMasternode, MNStates::MN_PRE_RESIGNED);
        }
    }

    protected function notifyResigned(UserMasternode $userMasternode): void
    {
        set_language($userMasternode->user->language);
        $success = $this->messageService->sendMessage(
            $userMasternode->user,
            __('mn_state_notification.resigned', [
                'name' => $userMasternode->name,
            ]),
            ['parse_mode' => 'Markdown']
        );
        if ($success) {
            $userMasternode->update([
                'is_active' => false,
            ]);
            $this->onetimeNotificationService->notificationSendForModel($userMasternode, MNStates::MN_RESIGNED);
        }
    }
}
