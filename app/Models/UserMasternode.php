<?php

namespace App\Models;

use App\Enum\MNStates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin \Eloquent
 * @property TelegramUser user
 * @property int          telegramUserId
 * @property string       name
 * @property integer      masternode_id
 * @property Masternode   masternode
 * @property bool         alarm_on
 * @property bool         synced_masternode_monitor
 * @property bool         is_active
 */
class UserMasternode extends Model
{
    protected $fillable = [
        'name',
        'masternode_id',
        'telegramUserId',
        'alarm_on',
        'synced_masternode_monitor',
        'is_active',
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'alarm_on',
        'synced_masternode_monitor',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class, 'telegramUserId');
    }

    public function masternode(): BelongsTo
    {
        return $this->belongsTo(Masternode::class);
    }

    public function mintedBlocks(): HasMany
    {
        return $this->hasMany(MintedBlock::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(OnetimeNotification::class, 'notifiable');
    }

    public function hasNotificationWithName(string $name): bool
    {
        return $this->notifications->where('name', $name)->count() > 0;
    }

    public function getOwnerAddressAttribute(): string
    {
        return $this->masternode->owner_address;
    }

    public function getOperatorAddressAttribute(): string
    {
        return $this->masternode->operator_address;
    }

    public function scopeSynced($query)
    {
        return $query->where('synced_masternode_monitor', true);
    }

    public function isEnabled(): bool
    {
        return $this->masternode->state === MNStates::MN_ENABLED;
    }

    public function isPreEnabled(): bool
    {
        return $this->masternode->state === MNStates::MN_PRE_ENABLED;
    }

    public function isResigned(): bool
    {
        return $this->masternode->state === MNStates::MN_RESIGNED;
    }

    public function isPreResigned(): bool
    {
        return $this->masternode->state === MNStates::MN_PRE_RESIGNED;
    }
}
