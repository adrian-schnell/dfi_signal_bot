<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @mixin \Eloquent
 * @property Carbon  date
 * @property integer user_count
 * @property integer masternode_count
 * @property integer messages_sent
 * @property integer commands_received
 * @property integer minted_blocks
 */
class Statistic extends Model
{
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d';
    protected $fillable = [
        'date',
        'user_count',
        'masternode_count',
        'messages_sent',
        'commands_received',
        'minted_blocks',
    ];
    protected $hidden = [
        'id',
    ];
}
