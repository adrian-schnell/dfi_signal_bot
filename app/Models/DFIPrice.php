<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property string ticker
 * @property string symbol
 * @property float price
 * @property float volume_24h
 * @property float volume_24h_change_24h
 * @property float market_cap
 * @property float market_cap_change_24h
 * @property float percent_change_15m
 * @property float percent_change_30m
 * @property float percent_change_1h
 * @property float percent_change_6h
 * @property float percent_change_12h
 * @property float percent_change_24h
 * @property float percent_change_7d
 * @property float percent_change_30d
 * @property float percent_change_1y
 * @property float ath_price
 * @property Carbon ath_date
 * @property float percent_from_price_ath
 * @property bool is_rounded
 */
class DFIPrice extends Model
{
    protected $table = 'dfi_prices';

    protected $dates = [
        'ath_date',
    ];

    protected $fillable = [
        'ticker',
        'symbol',
        'price',
        'volume_24h',
        'volume_24h_change_24h',
        'market_cap',
        'market_cap_change_24h',
        'percent_change_15m',
        'percent_change_30m',
        'percent_change_1h',
        'percent_change_6h',
        'percent_change_12h',
        'percent_change_24h',
        'percent_change_7d',
        'percent_change_30d',
        'percent_change_1y',
        'ath_price',
        'ath_date',
        'percent_from_price_ath',
        'is_rounded',
    ];
}
