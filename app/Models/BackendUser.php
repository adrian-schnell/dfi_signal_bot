<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @mixin \Eloquent
 * @property string name
 * @property string email
 * @property Carbon email_verified_at
 */
class BackendUser extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'password',
        'id',
        'email_verified_at',
    ];
}
