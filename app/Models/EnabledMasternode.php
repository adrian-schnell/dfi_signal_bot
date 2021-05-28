<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property string masternode_id
 * @property string owner_address
 * @property string operator_address
 * @property integer creation_height
 * @property string state
 * @property integer minted_blocks
 * @property integer target_multiplier
 */
class EnabledMasternode extends Model
{
    protected $fillable = [
        'masternode_id',
        'owner_address',
        'operator_address',
        'creation_height',
        'state',
        'minted_blocks',
        'target_multiplier',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
