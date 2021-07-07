<?php

namespace App\Listeners;

use App\Enum\QueueNames;
use App\Events\MnEnabledEvent;
use App\Jobs\MnEnabledNotificationJob;
use App\Models\UserMasternode;

class MnEnabledListener
{
    public function handle(MnEnabledEvent $event): void
    {
        $event->getMasternode()->userMasternodes->each(function (UserMasternode $userMasternode) {
            dispatch(new MnEnabledNotificationJob($userMasternode))
                ->onQueue(QueueNames::TELEGRAM_MESSAGE_OUTGOING);
        });
    }
}
