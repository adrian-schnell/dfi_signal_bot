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

    public function __construct()
    {
        $this->generalClient     = new Client([
            'base_uri'        => config('api_defichain.general.base_uri'),
            'timeout'         => 3,
            'connect_timeout' => 3,
        ]);
        $this->transactionClient = new Client([
            'base_uri'        => config('api_defichain.transaction.base_uri'),
            'timeout'         => 3,
            'connect_timeout' => 3,
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
                    'timeout'            => 3,
                    'connection_timeout' => 3,
                ])->getBody()->getContents(),
                true
            );
        } catch (Throwable $e) {
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
            throw DefichainApiException::generic(sprintf('API request to fetch latest block failed with message: %s',
                $e->getMessage()), $e);
        }
    }

    public function getLatestHash(): string
    {
        try {
            return $this->getStats()['bestBlockHash'];
        } catch (DefichainApiException $e) {
            throw DefichainApiException::generic(sprintf('API request to fetch latest block hash failed with message: %s',
                $e->getMessage()), $e);
        }
    }

    public function getBlockDetails(string $blockNumber): array
    {
        try {
            $rawResponse = $this->transactionClient->get(sprintf(config('api_defichain.transaction.block'),
                $blockNumber), [
                'timeout'            => 3,
                'connection_timeout' => 3,
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            return [];
        }

        return json_decode($rawResponse, true);
    }

    public function getPoolPairs(): array
    {
        try {
            $rawResponse = $this->generalClient->get(config('api_defichain.general.listpoolpairs'), [
                'timeout'            => 3,
                'connection_timeout' => 3,
            ])->getBody()->getContents();
        } catch (Throwable $e) {
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
                'timeout'            => 3,
                'connection_timeout' => 3,
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
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

    public function storeMintedBlockForTelegramUser(TelegramUser $user): void
    {
        $masternodes = $user->masternodes;

        $masternodes->each(function (UserMasternode $masternode) {
            dispatch(new StoreMintedBlocksJob($masternode))
                ->onQueue(QueueNames::MINTED_BLOCK_QUEUE);
        });
    }
}
