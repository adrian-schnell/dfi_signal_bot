<?php

namespace App\Listeners;

use App\Enum\QueueNames;
use App\Events\MnResignedEvent;
use App\Jobs\MnResignedNotificationJob;
use App\Models\UserMasternode;

class MnResignedListener
{
    public function handle(MnResignedEvent $event): void
    {
        $event->getMasternode()->userMasternodes->each(function (UserMasternode $userMasternode) {
            dispatch(new MnResignedNotificationJob($userMasternode))
                ->onQueue(QueueNames::TELEGRAM_MESSAGE_OUTGOING);
        });
    }
}
