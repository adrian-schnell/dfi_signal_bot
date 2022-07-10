<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Masternode extends Model
{
    protected $fillable = [
        'masternode_id',
        'owner_address',
        'operator_address',
        'creation_height',
        'resign_height',
        'state',
        'minted_blocks',
        'target_multipliers',
        'timelock',
    ];
    protected $casts = [
        'target_multipliers' => 'array',
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function userMasternodes(): HasMany
    {
        return $this->hasMany(UserMasternode::class);
    }
}
