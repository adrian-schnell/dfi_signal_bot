<?php

namespace App\Models;

use App\Models\Concerns\UsesUuidPrimary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Eloquent
 * @property integer      telegram_user_id
 * @property string       name
 * @property TelegramUser user
 * @property boolean      alarm_on
 */
class Server extends Model
{
    use UsesUuidPrimary;

    protected $fillable = [
        'name',
        'telegram_user_id',
        'alarm_on'
    ];
    protected $hidden = [
        'telegram_user_id',
        'alarm_on',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id');
    }

    public function stats(): HasMany
    {
        return $this->hasMany(ServerStat::class);
    }

    public function statType(string $type): HasMany
    {
        return $this->hasMany(ServerStat::class)->where('name', $type);
    }
}
