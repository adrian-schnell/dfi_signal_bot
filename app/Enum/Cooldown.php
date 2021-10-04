<?php

namespace App\Enum;

/**
 * @docs https://github.com/Kurozora/laravel-cooldown
 */
class Cooldown
{
    const LOCAL_SPLIT_NOTIFICATION = 'local_chain_split_notification';
    const REMOTE_SPLIT_NOTIFICATION = 'remote_chain_split_notification';
    const COOLDOWN_HOURS = [
        self::LOCAL_SPLIT_NOTIFICATION  => 2,
        self::REMOTE_SPLIT_NOTIFICATION => 2,
    ];
}
