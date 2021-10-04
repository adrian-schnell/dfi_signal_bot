<?php

namespace App\Jobs;

use App\Http\Service\TelegramMessageService;
use App\Models\UserMasternode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MnPreResignedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected UserMasternode $userMasternode;
    protected int $resignHeight;

    public function __construct(UserMasternode $userMasternode, int $resignHeight)
    {
        $this->userMasternode = $userMasternode;
        $this->resignHeight = $resignHeight;
    }

    public function handle(TelegramMessageService $messageService): void
    {
        set_language($this->userMasternode->user->language);
        $messageService->sendMessage(
            $this->userMasternode->user,
            __('mn_state_notification.pre_resigned', [
                'name'          => $this->userMasternode->name,
                'resignedBlock' => $this->resignHeight,
            ]),
            [
                'parse_mode' => 'Markdown',
            ]);
    }
}
