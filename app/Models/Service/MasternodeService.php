<?php

namespace App\Models\Service;

use App\Models\DfiMasternode;
use App\Models\TelegramUser;
use Illuminate\Database\QueryException;

class MasternodeService
{
    public function createMasternodeForUser(TelegramUser $user, string $ownerAddress, string $name, bool $alarmEnabled): bool
    {
        try {
            DfiMasternode::create([
                'telegramUserId' => $user->id,
                'name'           => $name,
                'owner_address'  => $ownerAddress,
                'alarm_on'       => $alarmEnabled,
            ]);
            return true;
        } catch (QueryException $e) {
            return false;
        }
    }
}
