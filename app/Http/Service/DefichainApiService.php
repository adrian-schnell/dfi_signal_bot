<?php

namespace app\Http\Service;

use App\Exceptions\DefichainApiException;
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

    public function mintedBlocksForOwnerAddress(string $ownerAddress)
    {
        $rawResponse = $this->transactionClient->get(sprintf('address/%s/txs',
            $ownerAddress))->getBody()->getContents();

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
}
