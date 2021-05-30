<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Eloquent
 * @property integer user_masternode_id
 * @property UserMasternode userMasternode
 * @property integer mintBlockHeight
 * @property integer spentBlockHeight
 * @property string block_hash
 * @property string spent_txid
 * @property string mint_txid
 * @property float value
 * @property string address
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon block_time
 */
class MintedBlock extends Model
{
    protected $fillable = [
        'user_masternode_id',
        'mintBlockHeight',
        'spentBlockHeight',
        'spent_txid',
        'mint_txid',
        'value',
        'address',
        'block_time',
        'block_hash',
    ];

    protected $dates = [
        'block_time',
    ];

    public function userMasternode(): BelongsTo
    {
        return $this->belongsTo(UserMasternode::class);
    }
}
