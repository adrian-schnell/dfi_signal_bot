<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Eloquent
 * @property TelegramUser user
 * @property int telegramUserId
 * @property string name
 * @property integer masternode_id
 * @property Masternode masternode
 * @property bool alarm_on
 * @property bool synced_masternode_monitor
 */
class UserMasternode extends Model
{
    protected $fillable = [
        'name',
        'masternode_id',
        'telegramUserId',
        'alarm_on',
        'synced_masternode_monitor',
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'alarm_on',
        'synced_masternode_monitor',
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
}
