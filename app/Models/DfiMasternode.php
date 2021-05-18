<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property TelegramUser user
 * @property int telegramUserId
 * @property string name
 * @property string masternode_id
 * @property string owner_address
 * @property string operator_address
 * @property bool alarm_on
 * @property bool synced_masternode_monitor
 */
class DfiMasternode extends Model
{
    protected $fillable = [
        'name',
        'masternode_id',
        'telegramUserId',
        'owner_address',
        'operator_address',
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

    public function user()
    {
        return $this->belongsTo(TelegramUser::class, 'telegramUserId');
    }

    public function scopeSynced($query)
    {
        return $query->where('synced_masternode_monitor', true);
    }
}
