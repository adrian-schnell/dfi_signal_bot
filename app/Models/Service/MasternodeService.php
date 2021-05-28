<?php

namespace App\Models\Service;

use App\Models\UserMasternode;
use App\Models\TelegramUser;
use Illuminate\Database\QueryException;

class MasternodeService
{
    public function createMasternodeForUser(TelegramUser $user, string $ownerAddress, string $name, bool $alarmEnabled): bool
    {
        try {
            UserMasternode::create([
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

    public function userHasAddress(TelegramUser $user, string $ownerAddress): bool
    {
        return UserMasternode::where('owner_address', $ownerAddress)
                ->where('telegramUserId', $user->id)
                ->count() === 1;
    }

    public function deleteMasternode(TelegramUser $user, string $ownerAddress): bool
    {
        return UserMasternode::where('owner_address', $ownerAddress)
            ->where('telegramUserId', $user->id)
            ->delete();
    }
}
