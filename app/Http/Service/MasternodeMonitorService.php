<?php

namespace App\Http\Service;

use App\Models\Masternode;
use App\Models\TelegramUser;
use App\Models\UserMasternode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class MasternodeMonitorService
{
    public function syncMasternodesForUser(TelegramUser $user, string $syncKey): array
    {
        $client = new Client();
        try {
            $response = $client->get(sprintf(config('masternode_monitor.sync_mn'), $syncKey));
            $content  = json_decode($response->getBody()->getContents(), true);

            return $this->storeMasternodes($user, $content);
        } catch (ClientException $e) {
            return [];
        }
    }

    protected function storeMasternodes(TelegramUser $user, array $masternodes): array
    {
        if (count($masternodes) === 0) {
            return [];
        }
        $masternodeArray = [];
        $this->removeSyncedMasternodes($user, $masternodes);
        foreach ($masternodes as $masternode) {
            $mn = Masternode::where('owner_address', $masternode['ownerAddress'])->first();
            if (isset($mn)) {
                $masternodeArray[] = UserMasternode::updateOrCreate([
                    'telegramUserId' => $user->id,
                    'masternode_id'  => $mn->id,
                ],
                    [
                        'telegramUserId'            => $user->id,
                        'name'                      => $masternode['name'],
                        'masternode_id'             => $mn->id,
                        'synced_masternode_monitor' => true,
                        'alarm_on'                  => true,
                    ]
                );
            }
        }

        return $masternodeArray;
    }

    public function removeSyncedMasternodes(TelegramUser $user, array $masternodes): int
    {
        $ownerAdresses = collect($masternodes)->pluck('ownerAddress')->toArray();
        $mnToRemove = UserMasternode::where('telegramUserId', $user->id)
            ->synced()
            ->with('masternode')
            ->whereDoesntHave('masternode', function ($query) use ($ownerAdresses) {
                return $query->whereIn('owner_address', $ownerAdresses);
            });

        $telegramMessageService = app(TelegramMessageService::class);
        $mnToRemove->pluck('name')->each(function (string $name) use ($user, $telegramMessageService) {
            $telegramMessageService
                ->sendMessage(
                    $user,
                    __('resyncMasternodeMonitor.removed_mn_named', ['name' => $name,]),
                    ['parse_mode' => 'Markdown']
                );
        });

        return $mnToRemove->delete();
    }

    public function resetMasternodes(TelegramUser $user): void
    {
        $user->masternodes->each(function (UserMasternode $masternode) {
            $masternode->delete();
        });
    }
}
