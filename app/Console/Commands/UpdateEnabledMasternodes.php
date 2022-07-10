<?php

namespace App\Console\Commands;

use App\Enum\MNStates;
use App\Events\MnEnabledEvent;
use App\Events\MnPreResignedEvent;
use App\Events\MnResignedEvent;
use App\Models\Masternode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class UpdateEnabledMasternodes extends Command
{
    protected $signature = 'masternode:update-list';
    protected $description = 'Updates the masternode information from mydeficha.in';

    public function handle(Client $client): void
    {
        try {
            $response = $client->get(config('api_mydefichain.enabled_masternodes_uri'));
            $rawData = collect(json_decode($response->getBody()->getContents(), true));
        } catch (GuzzleException $e) {
            $this->error(sprintf('failed to load the masternodes from API, reason: %s', $e->getMessage()));
        }

        $rawData->chunk(1000)->each(function ($rawdatas) {
            $preparedData = [];

            foreach ($rawdatas as $id => $data) {
                $preparedData[] = [
                    'masternode_id'      => $id,
                    'owner_address'      => $data['ownerAuthAddress'],
                    'operator_address'   => $data['operatorAuthAddress'],
                    'creation_height'    => $data['creationHeight'],
                    'state'              => $data['state'],
                    'minted_blocks'      => $data['mintedBlocks'],
                    'target_multipliers' => json_encode($data['targetMultipliers'] ?? []),
                    'timelock'           => $data['timelock'] ?? null,
                    'resign_height'      => $data['resignHeight'],
                ];
            }
            $this->checkEnabledStateChange($preparedData);
            $this->checkPreResignedStateChange($preparedData);
            $this->checkResignedStateChange($preparedData);
            Masternode::upsert($preparedData, ['id', 'owner_address', 'operator_address']);
        });
    }

    /**
     * triggers MnEnabledEvent for masternodes changing state from
     * PRE_ENABLED to ENABLED
     */
    protected function checkEnabledStateChange(array $preparedData): void
    {
        $preparedData = collect($preparedData);
        $preparedStateMn = Masternode::whereIn('masternode_id', $preparedData->pluck('masternode_id')->all())
            ->whereState(MNStates::MN_PRE_ENABLED)
            ->get();

        if ($preparedStateMn->count() === 0) {
            return;
        }
        $preparedStateMn->each(function (Masternode $masternode) use ($preparedData) {
            $newMnData = $preparedData
                ->where('owner_address', $masternode->owner_address)
                ->where('operator_address', $masternode->operator_address)->first();

            if (isset($newMnData) && $newMnData['state'] === MNStates::MN_ENABLED) {
                event(new MnEnabledEvent($masternode));
            }
        });
    }

    protected function checkPreResignedStateChange(array $preparedData): void
    {
        $preparedData = collect($preparedData);
        $preparedStateMn = Masternode::whereIn('masternode_id', $preparedData->pluck('masternode_id')->all())
            ->whereState(MNStates::MN_ENABLED)
            ->get();

        if ($preparedStateMn->count() === 0) {
            return;
        }
        $preparedStateMn->each(function (Masternode $masternode) use ($preparedData) {
            $newMnData = $preparedData
                ->where('owner_address', $masternode->owner_address)
                ->where('operator_address', $masternode->operator_address)->first();

            if (isset($newMnData) && $newMnData['state'] === MNStates::MN_PRE_RESIGNED) {
                event(new MnPreResignedEvent($masternode, $newMnData['resign_height']));
            }
        });
    }

    protected function checkResignedStateChange(array $preparedData): void
    {
        $preparedData = collect($preparedData);
        $preparedStateMn = Masternode::whereIn('masternode_id', $preparedData->pluck('masternode_id')->all())
            ->whereState(MNStates::MN_PRE_RESIGNED)
            ->get();

        if ($preparedStateMn->count() === 0) {
            return;
        }
        $preparedStateMn->each(function (Masternode $masternode) use ($preparedData) {
            $newMnData = $preparedData
                ->where('owner_address', $masternode->owner_address)
                ->where('operator_address', $masternode->operator_address)->first();

            if (isset($newMnData) && $newMnData['state'] === MNStates::MN_RESIGNED) {
                event(new MnResignedEvent($masternode));
            }
        });
    }
}
