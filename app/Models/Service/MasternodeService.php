<?php

namespace App\Models\Service;

use App\Models\Masternode;
use App\Models\UserMasternode;
use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class MasternodeService
{
    public function countMasternodeForUserInput(string $input): int
    {
        return Masternode::where('owner_address', $input)
            ->orWhere('operator_address', $input)
            ->orWhere('masternode_id', $input)
            ->count();
    }

    public function createMasternodeForUser(
        TelegramUser $user,
        string $address,
        string $name,
        bool $alarmEnabled
    ): bool {
        try {
            $mn = Masternode::where('owner_address', $address)
                ->orWhere('operator_address', $address)
                ->orWhere('masternode_id', $address)
                ->firstOrFail();
            UserMasternode::create([
                'telegramUserId' => $user->id,
                'name'           => $name,
                'masternode_id'  => $mn->id,
                'alarm_on'       => $alarmEnabled,
            ]);
            return true;
        } catch (QueryException | ModelNotFoundException $e) {
            return false;
        }
    }

    public function userHasAddress(TelegramUser $user, string $address): bool
    {
        return UserMasternode::where('telegramUserId', $user->id)
                ->with('masternode')
                ->whereHas('masternode', function ($query) use ($address) {
                    $query->where('owner_address', $address)
                        ->orWhere('operator_address', $address)
                        ->orWhere('masternode_id', $address);
                })->count() === 1;
    }

    public function deleteMasternode(TelegramUser $user, string $address): bool
    {
        return UserMasternode::where('telegramUserId', $user->id)
            ->with('masternode')
            ->whereHas('masternode', function ($query) use ($address) {
                $query->where('owner_address', $address)
                    ->orWhere('operator_address', $address)
                    ->orWhere('masternode_id', $address);
            })->delete();
    }
}
