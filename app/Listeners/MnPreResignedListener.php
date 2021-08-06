<?php

namespace App\Listeners;

use App\Enum\QueueNames;
use App\Events\MnPreResignedEvent;
use App\Jobs\MnPreResignedNotificationJob;
use App\Models\UserMasternode;

class MnPreResignedListener
{
    public function handle(MnPreResignedEvent $event): void
    {
        $event->getMasternode()->userMasternodes->each(function (UserMasternode $userMasternode) {
            dispatch(new MnPreResignedNotificationJob($userMasternode))
                ->onQueue(QueueNames::TELEGRAM_MESSAGE_OUTGOING);
        });
    }
}
