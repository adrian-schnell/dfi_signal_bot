<?php

namespace App\Events;

use App\Models\Masternode;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MnPreResignedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Masternode $masternode;

    public function __construct(Masternode $masternode)
    {
        $this->masternode = $masternode;
    }

    public function getMasternode(): Masternode
    {
        return $this->masternode;
    }
}
