<?php

namespace App\Http\Service;

use App\Enum\QueueNames;
use App\Exceptions\DefichainApiException;
use App\Jobs\StoreMintedBlocksJob;
use App\Models\TelegramUser;
use App\Models\UserMasternode;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Log;
use Throwable;

class DefichainApiService
{
    protected ClientInterface $generalClient;
    protected ClientInterface $transactionClient;
    protected ClientInterface $oceanClient;

    public function __construct()
    {
        $this->generalClient     = new Client([
            'base_uri'        => config('api_defichain.general.base_uri'),
            'timeout'         => 5,
            'connect_timeout' => 5,
        ]);
        $this->transactionClient = new Client([
            'base_uri'        => config('api_defichain.transaction.base_uri'),
            'timeout'         => 5,
            'connect_timeout' => 5,
        ]);
        $this->oceanClient = new Client([
            'base_uri'        => config('api_defichain.ocean.base_uri'),
            'timeout'         => 5,
            'connect_timeout' => 5,
        ]);
    }

    /**
     * @throws DefichainApiException
     */
    public function getStats(): array
    {
        try {
            return json_decode(
                $this->generalClient->get(config('api_defichain.general.stats'), [
                    'timeout'            => 5,
                    'connection_timeout' => 5,
                ])->getBody()->getContents(),
                true
            );
        } catch (Throwable $e) {
            Log::error('failed loading stats', [
                'file'    => $e->getFile(),
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);
            throw DefichainApiException::generic($e->getMessage(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\DefichainApiException
     */
    public function getLatestBlock(): int
    {
        try {
            return $this->getStats()['blockHeight'];
        } catch (DefichainApiException $e) {
            Log::error('failed loading latest block', [
                'file'    => $e->getFile(),
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);
            throw DefichainApiException::generic(sprintf('API request to fetch latest block failed with message: %s',
                $e->getMessage()), $e);
        }
    }

    public function getLatestHash(): string
    {
        try {
            return $this->getStats()['bestBlockHash'];
        } catch (DefichainApiException $e) {
            Log::error('failed loading latest block', [
                'file'    => $e->getFile(),
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);
            throw DefichainApiException::generic(sprintf('API request to fetch latest block hash failed with message: %s',
                $e->getMessage()), $e);
        }
    }

    public function getCurrentReward(): float
    {
        return cache()->remember('minter_reward', now()->addMinutes(5), function (){
            try {
                return $this->getStats()['rewards']['minter'] ?? -1;
            } catch (DefichainApiException $e) {
                Log::error('failed loading latest reward', [
                    'file'    => $e->getFile(),
                    'message' => $e->getMessage(),
                    'line'    => $e->getLine(),
                ]);
                throw DefichainApiException::generic(sprintf('API request to fetch latest reward failed with message: %s',
                    $e->getMessage()), $e);
            }
        });
    }

    public function getBlockDetails(string $blockNumber): array
    {
        try {
            $rawResponse = $this->transactionClient->get(sprintf(config('api_defichain.transaction.block'),
                $blockNumber), [
                'timeout'            => 5,
                'connection_timeout' => 5,
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            Log::error('failed loading block details', [
                'file'         => $e->getFile(),
                'block_number' => $blockNumber,
                'message'      => $e->getMessage(),
                'line'         => $e->getLine(),
            ]);

            return [];
        }

        return json_decode($rawResponse, true);
    }

    public function getPoolPairs(): array
    {
        try {
            $rawResponse = $this->generalClient->get(config('api_defichain.general.listpoolpairs'), [
                'timeout'            => 5,
                'connection_timeout' => 5,
            ])->getBody()->getContents();
        } catch (Throwable $e) {
            Log::error('failed loading pool pairs', [
                'file'    => $e->getFile(),
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);
            throw DefichainApiException::generic(sprintf('Failed to load poolpairs with message: %s',
                $e->getMessage()), $e);
        }

        return json_decode($rawResponse, true);
    }

    public function getTransactionDetails(string $txid): array
    {
        try {
            $rawResponse = $this->transactionClient->get(sprintf(config('api_defichain.transaction.tx'),
                $txid), [
                'timeout'            => 5,
                'connection_timeout' => 5,
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            Log::error('failed loading transaction details', [
                'file'    => $e->getFile(),
                'txid'    => $txid,
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);

            return [];
        }

        return json_decode($rawResponse, true);
    }

    public function mintedBlocksForOwnerAddress(string $ownerAddress): array
    {
        try {
            $rawResponse = $this->transactionClient->get(sprintf(config('api_defichain.transaction.address'),
                $ownerAddress), [
                'timeout'            => 10,
                'connection_timeout' => 10,
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            Log::error('failed loading minted blocks', [
                'file'          => $e->getFile(),
                'owner_address' => $ownerAddress,
                'message'       => $e->getMessage(),
                'line'          => $e->getLine(),
            ]);

            return [];
        }

        $txs            = json_decode($rawResponse, true);
        $mintedBlockTxs = [];
        foreach ($txs as $tx) {
            if (!$tx['coinbase']) {
                continue;
            }
            $mintedBlockTxs[] = $tx;
        }

        return $mintedBlockTxs;
    }

    public function storeMintedBlockForTelegramUser(TelegramUser $user, bool $initMode = true): void
    {
        $masternodes = $user->masternodes;

        $masternodes->each(function (UserMasternode $masternode) use ($initMode, $user) {
            dispatch(new StoreMintedBlocksJob($masternode->id, $user->id, $initMode))
                ->onQueue(QueueNames::MINTED_BLOCK_QUEUE);
        });
    }
}
