<?php

namespace App\Http\Service;

use App\Models\Server;
use App\Models\TelegramUser;
use Str;

class ServerSyncService
{
    public function generateApiKey(TelegramUser $user): string
    {
        if ($user->user_sync_key) {
            return $user->user_sync_key;
        }

        return $this->regenerateApiKey($user);
    }

    public function regenerateApiKey(TelegramUser $user): string
    {
        $sync_key = Str::uuid();

        $user->update([
            'user_sync_key' => $sync_key,
        ]);

        return $sync_key;
    }

    public function addServerForUser(TelegramUser $user, string $name): string
    {
        $server = Server::create([
            'telegram_user_id' => $user->id,
            'name'             => $name,
        ]);

        return $server->id;
    }
}
