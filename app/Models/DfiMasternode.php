<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property TelegramUser user
 * @property int          telegramUserId
 * @property string       masternode_id
 * @property string       owner_address
 * @property string       operator_address
 */
class DfiMasternode extends Model
{
}
