<?php

return [
    'ask_owner_address' => '*Whats the owner address of your masternode?*',
    'ask_name'          => '*Whats the name of this masternode?*
    (enter *random* to generate an automatic name)',
    'ask_alarm'         => '*would you like to be informed about special events?*',

    'final' => 'Your masternode is setup now 🚀',

    'buttons' => [
        'yes' => 'Yes, please 🙏',
        'no'  => 'no thanks',
    ],

    'error' => [
        'invalid_owner_address' => '*This address seems to be invalid. Please enter the owner address:*',
        'other_user_linked' => '*This address is already linked with another user. Please enter another owner address or /stop this command:*',
        'duplicated_address'    => 'This address is already setup 🙈',
    ],
];
