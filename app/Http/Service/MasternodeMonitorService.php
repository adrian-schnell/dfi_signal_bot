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
        foreach ($masternodes as $masternode) {
            $mn                = Masternode::where('owner_address', $masternode['ownerAddress'])->first();
            $masternodeArray[] = UserMasternode::updateOrCreate([
                'telegramUserId' => $user->id,
                'masternode_id'  => $mn->id,
            ],
                [
                    'telegramUserId'            => $user->id,
                    'name'                      => $masternode['name'],
                    'masternode_id'             => $mn->id,
                    'synced_masternode_monitor' => true,
                ]
            );
        }

        return $masternodeArray;
    }

    public function resetMasternodes(TelegramUser $user): void
    {
        $user->masternodes->each(function (UserMasternode $masternode) {
            $masternode->delete();
        });
    }
}
