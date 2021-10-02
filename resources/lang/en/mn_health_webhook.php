<?php

return [
    'headline'             => [
        'warnings'        => '🚧🚧🚧 *MN Health Warnings* 🚧🚧🚧',
        'critical' => '🆘🆘🆘 *MN Health Critical Issues* 🆘🆘🆘',
    ],
    'latest_server_update' => '`Data based on latest server update :human_readable ago (:date)`',
    'cooldown_message'     => "{1}`NOTICE: This message will be snoozed for the next hour`|[2,*]`NOTICE: This message will be snoozed for the next :value hours`",

    'warnings' => [
        'block_height'     => "👉 *Block Height*\r\nYour local node has block height :value and the main net is at block :expected. The node is :difference blocks behind.",
        'connection_count' => "👉 *Connection Count*\r\nAt the moment there are only :value active connections. Maybe the node recently restartet, otherwise it's recommended to add more connections with the command `addnode \"seed.mydeficha.in:8555\" add`.",
        'logsize'          => "👉 *Logsize quite big*\r\nYour `debug.log` appears to be quite bit with :size MB. Maybe your node has a problem...",
        'config_checksum'  => "👉 *Config Checksum changed*\r\nThe checksum of your `defi.conf` file changed. If you did not change anything, take a look at this file. Maybe your server got hacked..?",
        'node_version'     => "👉 *Please update your node*\r\nYour node is running on version :value - the current available version is :expected. Please update your node soon.",

        'load_avg'              => "👉 *Server load*\r\nThe current system load of your server (:value) is quite high and needs your attention!",
        'hdd'                   => "👉 *HDD capacity*\r\nAt the moment, *:value%* of your HDD capacity is used.",
        'ram'                   => "👉 *RAM capacity*\r\nAt the moment, *:value%* of your RAM capacity is used.",
        'server_script_version' => "👉 *Server Script needs update*\r\nInstalled version is :value, but current version is :expected. Please upgrade with `pip3 install --upgrade masternode-health`",
    ],

    'critical' => [
        'block_height'    => "🆘 *Block Height - possible chainsplit* 🆘\r\nYour local node is :difference blocks in front of the main net. Maybe the main net API just stopped working. Otherwise it's a clear sign that your node had a chainsplit. Please check your `debug.log`.",
        'block_hash'      => "🆘 *Block Hash - possible chainsplit* 🆘\r\nYour node's hash for block `:value` differs from main net. It should be `:expected.`",
        'defid_running'   => "🆘 *DEFID not running* 🆘\r\nThe defid service seems to be either not running or not responding. It is possible that your node won't mint new blocks at the moment.",
        'operator_status' => "🆘 *Operator not online* 🆘\r\n:value of your operators are not online at the moment.",

        'load_avg' => "🆘 *Server load* 🆘\r\nThe current system load of your server (:value) seems to be critical and needs your attention!",
        'hdd'      => "🆘 *HDD capacity* 🆘\r\nAt the moment, *:value%* of your HDD capacity is used.",
        'ram'      => "🆘 *RAM capacity* 🆘\r\nAt the moment, *:value%* of your RAM capacity is used.",
    ],
];
