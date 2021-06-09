<?php

return [
    'intro'    => '*Your Masternode Monitor sync key changed?* Let\'s update it now.',
    'question' => 'Enter your new sync key now:',

    'result'             => [
        'updated'     => 'Your sync key was updated ✅',
        'no_masternodes'     => 'You\'ve no masternodes stored in this Masternode Monitor with this sync key. Please try again by starting with command /sync',
        'masternodes_synced' => '{1}You synced one masternode with this bot|[2,*]You synced :number masternodes with this bot.',
    ]
];
