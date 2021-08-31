<?php

namespace App\Enum;

class ServerStatTypes
{
    const LOAD_AVG = 'load_avg';
    const NUM_CORES = 'num_cores';
    const RAM_USED = 'ram_used';
    const RAM_TOTAL = 'ram_total';
    const HDD_USED = 'hdd_used';
    const HDD_TOTAL = 'hdd_total';

    const NODE_UPTIME = 'node_uptime';
    const NODE_VERSION = 'node_version';
    const BLOCK_HEIGHT = 'block_height_local';
    const LOCAL_HASH = 'local_hash';
    const OPERATOR_STATUS = 'operator_status';
    const CONNECTION_COUNT = 'connection_count';
    const LOGSIZE = 'logsize';
    const CONFIG_CHECKSUM = 'config_checksum';
}
