<?php

namespace App\Console\Commands;

use App\Coinpaprika\CoinpaprikaApi;
use App\Enum\CoinSymbol;
use App\Models\DFIPrice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateDFIPricesCommand extends Command
{
	protected $signature = 'update:dfi-prices';

	protected $description = 'Update DFI prices from coinpaprika api';

    public function handle(CoinpaprikaApi $api): void
    {
        $rates = $api->getDfiRates();

        $preparedData = [];
        collect($rates)->each(function (array $rate, string $ticker) use (&$preparedData) {
            $preparedData[] = [
                'ticker'                 => $ticker,
                'symbol'                 => CoinSymbol::MAPPING[$ticker] ?? null,
                'price'                  => $rate['price'],
                'volume_24h'             => $rate['volume_24h'],
                'volume_24h_change_24h'  => $rate['volume_24h_change_24h'],
                'market_cap'             => $rate['market_cap'],
                'market_cap_change_24h'  => $rate['market_cap_change_24h'],
                'percent_change_15m'     => $rate['percent_change_15m'],
                'percent_change_30m'     => $rate['percent_change_30m'],
                'percent_change_1h'      => $rate['percent_change_1h'],
                'percent_change_6h'      => $rate['percent_change_6h'],
                'percent_change_12h'     => $rate['percent_change_12h'],
                'percent_change_24h'     => $rate['percent_change_24h'],
                'percent_change_7d'      => $rate['percent_change_7d'],
                'percent_change_30d'     => $rate['percent_change_30d'],
                'percent_change_1y'      => $rate['percent_change_1y'],
                'ath_price'              => $rate['ath_price'],
                'ath_date'               => Carbon::parse($rate['ath_date']),
                'percent_from_price_ath' => $rate['percent_from_price_ath'],
            ];
        });
        DFIPrice::upsert($preparedData, ['ticker']);
    }
}
