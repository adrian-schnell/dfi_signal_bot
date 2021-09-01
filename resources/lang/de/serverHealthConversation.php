<?php

return [
    'no_data' => "❌ Es wurden noch keine Serverdaten übertragen. Hast du die Einrichtungsschritte alle korrekt befolgt und mindestens einen Cron-Aufruf gewartet?\r\n\r\nAnsonsten führe diesen Befehl von Hand auf deinem Server aus:\r\n`masternode-health --verbose --report --api-key=your-api-key`",
    'latest_update' => "\r\n\r\nzuletzt aktualisiert: :time",

    'server_stats' => [
        'disk_usage'  => "Festplatte:\t:used/:total GB\r\n `:progress`",
        'ram_usage'   => "\r\n\r\nArbeitsspeicher:\t:used/:total GB\r\n `:progress`",
        'system_load' => "\r\n\r\nSystemlast:\r\n `:progress`",
    ],
    'nodeInfo'     => [
        'block_height'     => "*Block Höhe:*\t\t\t:value",
        'block_hash'       => "\r\n*Block Hash:*\t\t\t\t[:value](:link)",
        'log_size'         => "\r\n*Log Datei Größe:*\t\t:value MB",
        'connection_count' => "\r\n*Verbindungen:* :value",
        'node_version'     => "\r\n*Node Version:* :value",
        'node_uptime'      => "\r\n*Node Betriebszeit:* :value",
        'operator_status'  => "\r\n\r\n*Operator Status:*",
    ],
];
