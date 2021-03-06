<?php

return [
    'intro'    => 'Now you can enter your sync key from <a href="https://www.defichain-masternode-monitor.com">Masternode Monitor</a>',
    'question' => 'Enter your sync key now:',

    'question_store_key' => 'Do you want to save your sync key? Then i detect new masternodes automatically. You can of course change or remove the sync key at any time.',
    'question_store_key_repeat' => 'Do you want to save your sync key? Please choose yes or no.',

    'buttons'            => [
        'yes' => 'Yes, please 🙏',
        'no'  => 'no thanks',
    ],

    'result'             => [
        'no_masternodes'     => 'You\'ve no masternodes stored in this Masternode Monitor with this sync key. Please try again by starting with command /sync',
        'masternodes_synced' => '{1}You synced one masternode with this bot|[2,*]You synced :number masternodes with this bot.',
    ]
];
