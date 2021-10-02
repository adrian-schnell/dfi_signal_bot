<?php

return [
    'headline'             => [
        'warnings'        => '🚧🚧🚧 *MN Health Warnings* 🚧🚧🚧',
        'critical_errors' => '🆘🆘🆘 *MN Health Critical Issues* 🆘🆘🆘',
    ],
    'latest_server_update' => 'Data based on latest server update :human_readable (:date)',
    'cooldown_for_hours'   => 'This notification is muted for the next :value hours before you\'ll receive it again.',

    'warnings' => [
        'block_height'     => "⚠️ *Block Height*\r\nYour local node has block height :value and the main net is at block :expected. The node is :difference blocks behind.",
        'connection_count' => "⚠️ *Connection Count*\r\nAt the moment there are only :value connections to the node. Maybe the node recently restartet. Otherwise it's recommended to add more connections.",
        'logsize'          => "⚠️ *Logsize quite big*\r\nYour `debug.log` appears to be quite bit with :size MB. Maybe your node has a problem...",
        'config_checksum'  => "⚠️ *Config Checksum changed*\r\nThe checksum of your `defi.conf` file changed. If you did not change anything, take a look at this file. Maybe your server got hacked..?",
        'node_version'     => "⚠️ *Please update your node*\r\nYour node is running on version :value - the current available version is :expected. Please update your node soon.",

        'load_avg'              => "⚠️ *Server load*\r\nThe current system load of your server (:value) is quite high and needs your attention!",
        'hdd'                   => "⚠️ *HDD capacity*\r\nAt the moment, :value percent of your HDD capacity is used.",
        'ram'                   => "⚠️ *RAM capacity*\r\nAt the moment, :value percent of your RAM capacity is used.",
        'server_script_version' => "⚠️ *Server Script needs update*\r\nInstalled version is :value, but current version is :expected. Please upgrade with `pip3 install --upgrade masternode-health`",
    ],

    'critical' => [
        'block_height' => "🆘 *Block Height - possible chainsplit*\r\nYour local node is :difference blocks in front of the main net. Maybe the main net API just stopped working. Otherwise it's a clear sign that your node had a chainsplit. Please check your `debug.log`.",
    ],
];
