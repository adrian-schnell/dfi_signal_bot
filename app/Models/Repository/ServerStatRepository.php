<?php

namespace App\Models\Repository;

use App\Models\Server;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServerStatRepository
{
    public function getLatestTypeForServer(Server $server, string $type): string
    {
        try {
            $serverStat = $server->stats()->where('type', $type)
                ->orderBy('created_at', 'DESC')
                ->firstOrFail();

            return $serverStat->value;
        } catch (ModelNotFoundException $e) {
            return '';
        }
    }
}
