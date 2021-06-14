<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Nova\Actions\Actionable;

/**
 * @mixin \Eloquent
 * @property string telegramId
 * @property string firstName
 * @property string lastName
 * @property string username
 * @property string language
 * @property string mn_monitor_sync_key
 * @property string status
 * @property string user_sync_key
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class TelegramUser extends Model
{
    use Actionable;
    protected $fillable = [
        'telegramId',
        'firstName',
        'lastName',
        'username',
        'language',
        'mn_monitor_sync_key',
        'status',
        'user_sync_key',
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function masternodes(): HasMany
    {
        return $this->hasMany(UserMasternode::class, 'telegramUserId');
    }

    public function masternodesSynced(): HasMany
    {
        return $this->hasMany(UserMasternode::class, 'telegramUserId')->synced();
    }

    public function server(): HasMany
    {
        return $this->hasMany(Server::class);
    }
}
