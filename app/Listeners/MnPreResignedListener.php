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
        $event->getMasternode()->userMasternodes->each(function (UserMasternode $userMasternode) use ($event){
            dispatch(new MnPreResignedNotificationJob($userMasternode, $event->getResignHeight()))
                ->onQueue(QueueNames::TELEGRAM_MESSAGE_OUTGOING);
        });
    }
}
