<?php

namespace App\Enum;

class ServerStatTypes
{
    const CPU = 'cpu';
    const RAM_USED = 'ram_used';
    const RAM_TOTAL = 'ram_total';
    const HDD_USED = 'hdd_used';
    const HDD_TOTAL = 'hdd_total';
    const CONNECTIONCOUNT = 'connectioncount';
    const BLOCK_HEIGHT = 'block_height_local';
    const BLOCK_DIFF = 'block_diff';
    const LOCAL_HASH = 'local_hash';
    const LOGSIZE = 'logsize';
    const GENERIC_TYPES = [
        self::CPU,
        self::RAM_USED,
        self::RAM_TOTAL,
        self::HDD_USED,
        self::HDD_TOTAL,
        self::CONNECTIONCOUNT,
        self::BLOCK_HEIGHT,
        self::BLOCK_DIFF,
        self::LOCAL_HASH,
        self::LOGSIZE,
    ];
}
