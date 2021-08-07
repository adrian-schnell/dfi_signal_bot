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
    protected int $resignHeight;

    public function __construct(Masternode $masternode, int $resignHeight)
    {
        $this->masternode = $masternode;
        $this->resignHeight = $resignHeight;
    }

    public function getMasternode(): Masternode
    {
        return $this->masternode;
    }

    public function getResignHeight(): int
    {
        return $this->resignHeight;
    }
}
