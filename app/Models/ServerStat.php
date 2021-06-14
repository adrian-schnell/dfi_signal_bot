<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 * @property integer server_id
 * @property Server  server
 * @property string  name
 * @property string  value
 * @property Carbon  updated_at
 * @property Carbon  created_at
 */
class ServerStat extends Model
{
    protected $hidden = [
        'id',
        'server_id',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'server_id',
        'name',
        'value',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
