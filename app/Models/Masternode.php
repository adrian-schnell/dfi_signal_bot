<?php

namespace App\Models;

use App\Enum\MNStates;
use App\Events\MnEnabledEvent;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Eloquent
 * @property string masternode_id
 * @property string owner_address
 * @property string operator_address
 * @property integer creation_height
 * @property integer resign_height
 * @property integer ban_height
 * @property string state
 * @property integer minted_blocks
 * @property integer target_multiplier
 */
class Masternode extends Model
{
    protected $fillable = [
        'masternode_id',
        'owner_address',
        'operator_address',
        'creation_height',
        'resign_height',
        'ban_height',
        'state',
        'minted_blocks',
        'target_multiplier',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public static function boot()
    {
        parent::boot();
        static::updating(function (Masternode $masternode) {
            // this masternode switched from pre_enabled to enabled
            if ($masternode->isDirty('state') && $masternode->state === MNStates::MN_ENABLED &&
                $masternode->getOriginal('state') === MNStates::MN_PRE_ENABLED) {
                event(new MnEnabledEvent($masternode));
            }
        });
    }

    public function userMasternodes(): HasMany
    {
        return $this->hasMany(UserMasternode::class);
    }
}
