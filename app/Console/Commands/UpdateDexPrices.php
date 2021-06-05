<?php

namespace App\Console\Commands;

use App\Enum\CoinSymbol;
use App\Enum\DexCoin;
use App\Exceptions\DefichainApiException;
use App\Http\Service\DefichainApiService;
use App\Models\DEXPrice;
use Illuminate\Console\Command;

class UpdateDexPrices extends Command
{
    protected $signature = 'update:dex-prices';
    protected $description = 'Update prices from the DEX';

    public function handle(DefichainApiService $service): int
    {
        try {
            $poolpairs = $service->getPoolPairs();
        } catch (DefichainApiException $e) {
            $this->error(sprintf('API Error with message: %s', $e->getMessage()));

            return 1;
        }

        $dataPreparation = [];

        collect($poolpairs)
            ->filter(function (array $data, int $dexId) {
                return array_key_exists($dexId, DexCoin::POOL_MAPPING);
            })
            ->each(function (array $data, int $dexId) use (&$dataPreparation) {
                $dataPreparation[] = [
                    'dex_id'         => $dexId,
                    'name'           => DexCoin::DEX_MAPPING[$data['idTokenA']],
                    'price_to_dfi'   => $data['reserveA/reserveB'],
                    'price_from_dfi' => $data['reserveB/reserveA'],
                ];
            });
        DEXPrice::upsert($dataPreparation, ['dex_id', 'name']);
        $this->info('Updated DEX prices');
        ray($poolpairs, $dataPreparation);

        return 0;
    }
}
