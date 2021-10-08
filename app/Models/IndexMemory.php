<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property string  type
 * @property integer value
 */
class IndexMemory extends Model
{
    protected $fillable = [
        'value',
        'type',
    ];
}
