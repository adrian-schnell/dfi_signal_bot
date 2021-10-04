<?php

namespace App\Enum;

/**
 * @url https://defichain-wiki.com/wiki/API
 */
class DexCoin
{
    const DEX_MAPPING = [
        0  => 'DFI',
        1  => 'ETH',
        2  => 'BTC',
        3  => 'USDT',
        7  => 'DOGE',
        9  => 'LTC',
        11 => 'BCH',
        13 => 'USDC',
    ];
    const POOL_MAPPING = [
        4  => 'ETH',
        5  => 'BTC',
        6  => 'USDT',
        8  => 'DOGE',
        10 => 'LTC',
        12 => 'BCH',
        14 => 'USDC',
    ];
}
