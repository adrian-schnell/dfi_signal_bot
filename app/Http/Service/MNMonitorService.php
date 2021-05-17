<?php

namespace App\Http\Service;

use App\Models\TelegramUser;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class MNMonitorService
{
    public function syncMasternodesForUser(TelegramUser $user, string $syncKey): array
    {
        $client = new Client();
        try {
            $response = $client->get(sprintf(config('masternode_monitor.sync_mn'), $syncKey));
            $content  = json_decode($response->getBody()->getContents(), true);
            $this->storeMasternodes($user, $content);
        } catch (ClientException $e) {
            $content = [];
        }

        return $content;
    }

    protected function storeMasternodes(TelegramUser $user, array $masternodes): void
    {
        if (count($masternodes) === 0) {
            return;
        }

        foreach ($masternodes as $masternode) {
            DfiMasternode::create([
                
            ]);
        }
    }
}
