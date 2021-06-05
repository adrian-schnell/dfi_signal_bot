<?php

namespace App\Models;

use App\Enum\CoinSymbol;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property integer dex_id
 * @property string  name
 * @property float   price_to_dfi
 * @property float   price_from_dfi
 */
class DEXPrice extends Model
{
    protected $table = 'dex_prices';

    public function getPriceAttribute(): float
    {
        return $this->price_to_dfi;
    }

    public function getSymbolAttribute(): string
    {
        return CoinSymbol::MAPPING[$this->name] ?? $this->name;
    }
}
