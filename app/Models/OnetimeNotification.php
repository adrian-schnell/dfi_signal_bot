<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property string name
 * @property string notifiable_type
 * @property int    notifiable_id
 * @property Carbon sent_at
 */
class OnetimeNotification extends Model
{
    public $timestamps = [
        'sent_at',
    ];
    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'name',
        'sent_at',
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'notifiable_id',
        'notifiable_type',
    ];
}
