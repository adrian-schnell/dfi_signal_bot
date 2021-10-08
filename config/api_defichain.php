<?php

return [
    'general' => [
        'base_uri'      => 'https://api.defichain.io/v1/',
        'stats'         => 'stats',
        'listpoolpairs' => 'listpoolpairs',
    ],

    'transaction' => [
        'base_uri' => 'https://mainnet-api.defichain.io/api/DFI/mainnet/',
        'tx'       => 'tx/%s',
        'address'  => 'address/%s/txs',
        'block'    => 'block/%s',
    ],
    'ocean'       => [
        'base_uri' => 'https://ocean.defichain.com/v0/mainnet/',
        'blocks'   => 'blocks/%s',
    ],
];
