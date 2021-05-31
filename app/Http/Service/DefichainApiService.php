<?php

namespace App\Http\Service;

use App\Exceptions\DefichainApiException;
use App\Models\Repository\MintedBlockRepository;
use App\Models\TelegramUser;
use App\Models\UserMasternode;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class DefichainApiService
{
    protected ClientInterface $generalClient;
    protected ClientInterface $transactionClient;

    public function __construct()
    {
        $this->generalClient = new Client([
            'base_uri' => config('api_defichain.general.base_uri'),
        ]);
        $this->transactionClient = new Client([
            'base_uri' => config('api_defichain.transaction.base_uri'),
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function getStats(): array
    {
        return json_decode(
            $this->generalClient->get(config('api_defichain.general.stats'))->getBody()->getContents(),
            true
        );
    }

    public function getLatestBlock(): int
    {
        try {
            return $this->getStats()['blockHeight'];
        } catch (GuzzleException $e) {
            throw DefichainApiException::generic('API request to fetch latest block failed', $e);
        }
    }

    public function getLatestHash(): string
    {
        try {
            return $this->getStats()['bestBlockHash'];
        } catch (GuzzleException $e) {
            throw DefichainApiException::generic('API request to fetch latest block hash failed', $e);
        }
    }

    public function getBlockDetails(string $blockNumber): array
    {
        try {
            $rawResponse = $this->transactionClient->get(sprintf(config('api_defichain.transaction.block'), $blockNumber))->getBody()->getContents();
        } catch (GuzzleException $e) {
            return [];
        }
        return json_decode($rawResponse, true);
    }

    public function getTransactionDetails(string $txid): array
    {
        try {
            $rawResponse = $this->transactionClient->get(sprintf(config('api_defichain.transaction.tx'), $txid))->getBody()->getContents();
        } catch (GuzzleException $e) {
            return [];
        }

        return json_decode($rawResponse, true);
    }

    public function mintedBlocksForOwnerAddress(string $ownerAddress): array
    {
        try {
            $rawResponse = $this->transactionClient->get(sprintf(config('api_defichain.transaction.address'),
                $ownerAddress))->getBody()->getContents();
        } catch (GuzzleException $e) {
            return [];
        }

        $txs = json_decode($rawResponse, true);
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
            $ownerAddress = $masternode->masternode->owner_address;
            $mintedBlocks = $this->mintedBlocksForOwnerAddress($ownerAddress);
            app(MintedBlockRepository::class)
                ->storeMintedBlocks($this,$masternode, $mintedBlocks);
        });
    }
}
