<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property TelegramUser user
 * @property int          telegramUserId
 * @property string       name
 * @property string       masternode_id
 * @property string       owner_address
 * @property string       operator_address
 */
class DfiMasternode extends Model
{
    protected $fillable = [
        'name',
        'masternode_id',
        'telegramUserId',
        'owner_address',
        'operator_address',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(TelegramUser::class, 'telegramUserId');
    }
}
