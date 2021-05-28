<?php

namespace App\Console\Commands;

use App\Models\EnabledMasternode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class UpdateEnabledMasternodes extends Command
{
    protected $signature = 'masternode:update';
    protected $description = 'Updates the masternode information from mydeficha.in';

    public function handle(Client $client): void
    {
        try {
            $response = $client->get(config('api_mydefichain.enabled_masternodes_uri'));
            $rawData = collect(json_decode($response->getBody()->getContents(), true));
        } catch (GuzzleException $e) {
            $this->error(sprintf('failed to load the masternodes from API, reason: %s', $e->getMessage()));
        }

        $rawData
            ->chunk(1000)->each(function ($rawdatas) {
            $preparedData = [];

            foreach ($rawdatas as $id => $data) {
                $preparedData[] = [
                    'masternode_id'     => $id,
                    'owner_address'     => $data['ownerAuthAddress'],
                    'operator_address'  => $data['operatorAuthAddress'],
                    'creation_height'   => $data['creationHeight'],
                    'state'             => $data['state'],
                    'minted_blocks'     => $data['mintedBlocks'],
                    'target_multiplier' => $data['targetMultiplier'],
                ];
            }
            EnabledMasternode::upsert($preparedData, ['id', 'owner_address', 'operator_address']);
        });
    }
}
