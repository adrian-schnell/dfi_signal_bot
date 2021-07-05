<?php

return [
    'intro'           => "*DFI Signal Server Health* - if you want to learn more: [Github :repo](https://github.com/:repo)\r\n\r\nSelect an option:",
    'repeat_question' => 'Select an option from above or /stop',

    'buttons' => [
        'api_key'       => 'api key settings',
        'list_server'   => 'List my servers',
        'add_server'    => 'add Server',
        'remove_server' => 'remove Server',
        'cancel'        => 'cancel',
    ],

    'api_key' => [
        'question' => 'Select an option',

        'get_api_key'        => 'Your api key is: `:api_key`',
        'regenerate_api_key' => 'Your new api key is `:api_key`',

        'buttons' => [
            'get_api_key'        => 'Grap your api key',
            'regenerate_api_key' => 'generate a new api key',
        ],
    ],

    'add_server' => [
        'ask_name'  => 'Choose a name for your new server:',
        'server_id' => "Server id for *:name*:\r\n`:server_id`",
    ],
];
