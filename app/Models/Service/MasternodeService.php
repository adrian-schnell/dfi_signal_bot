<?php

namespace App\Models\Service;

use App\Exceptions\DefichainApiException;
use App\Http\Service\DefichainApiService;
use App\Models\Masternode;
use App\Models\UserMasternode;
use App\Models\TelegramUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;

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

    public function otherUserHasAddress(TelegramUser $user, string $address): bool
    {
        return UserMasternode::where('telegramUserId', '!=',$user->id)
                ->with('masternode')
                ->whereHas('masternode', function ($query) use ($address) {
                    $query->where('owner_address', $address)
                        ->orWhere('operator_address', $address)
                        ->orWhere('masternode_id', $address);
                })->count() > 0;
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

    public function getBlockTime(string $blocknumber): Carbon
    {
        $creationBlockDetails = app(DefichainApiService::class)->getBlockDetails($blocknumber);

        return Carbon::parse($creationBlockDetails['time']);
    }

    /**
     * @throws \App\Exceptions\DefichainApiException
     */
    public function getCreationDateOfMasternode(UserMasternode $userMasternode): Carbon
    {
        try {
            $creationHeight       = $userMasternode->masternode->creation_height;
            $creationBlockDetails = app(DefichainApiService::class)->getBlockDetails($creationHeight);

            return Carbon::parse($creationBlockDetails['time']);
        } catch (Throwable $e) {
            throw DefichainApiException::generic('fetching block details failed', $e);
        }
    }

    /**
     * @throws \App\Exceptions\DefichainApiException
     */
    public function calculateMasternodeAge(UserMasternode $userMasternode, string $ageIn = 'days'): float
    {
        switch ($ageIn) {
            case 'days':
                return now()->diffInDays($this->getCreationDateOfMasternode($userMasternode));
            case 'hours':
                return now()->diffInHours($this->getCreationDateOfMasternode($userMasternode));
            default:
                return 0;
        }
    }
}
