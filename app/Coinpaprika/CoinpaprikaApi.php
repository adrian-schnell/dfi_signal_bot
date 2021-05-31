<?php

namespace App\Coinpaprika;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class CoinpaprikaApi
{
    protected ClientInterface $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('coinpaprika.base_uri'),
        ]);
    }

    public function getDfiRates(): array
    {
        return json_decode(
            $this->client->get(config('coinpaprika.ticker'))->getBody()->getContents(),
            true
        )['quotes'];
    }
}
