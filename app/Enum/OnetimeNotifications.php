<?php

namespace App\Enum;

class OnetimeNotifications
{
    const MN_PRE_ENABLED = 'mn_pre_enabled';
    const MN_ENABLED = 'mn_enabled';
    const MN_PRE_RESIGNED = 'mn_pre_resigned';
    const MN_RESIGNED = 'mn_resigned';
    const MN_STATES = [
        self::MN_PRE_ENABLED,
        self::MN_ENABLED,
        self::MN_PRE_RESIGNED,
        self::MN_RESIGNED,
    ];
}
