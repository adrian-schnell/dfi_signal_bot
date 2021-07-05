<?php

namespace App\Enum;

class MNStates
{
    const MN_PRE_ENABLED = 'PRE_ENABLED';
    const MN_ENABLED = 'ENABLED';
    const MN_PRE_RESIGNED = 'PRE_RESIGNED';
    const MN_RESIGNED = 'RESIGNED';

    const DESTRUCTIVE_STATES = [
        self::MN_PRE_RESIGNED,
        self::MN_RESIGNED,
    ];
}
