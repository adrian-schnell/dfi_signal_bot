<?php

return [
    'intro'                         => "*DeFiChain Masternode Server Health* is a service to provide information about your server & node for you. And it wouldn't be a notification service without alarms if any problem occurs.\r\n\r\nTogether with this service, DFI Signal notifies you if you're running out of memory, a chain split is detected or any other problem on your node occurs.\r\n\r\n`To use this service, you need to install a companion app on your server (instructions later in the setup process).`",
    'setup_method_selection'        => 'First step to setup masternode health, you need an API key. I can generate a new one for you now - otherwise you can use an existing one.',
    'setup_method_selection_repeat' => 'Please choose an option above offered by the buttons!',
    'setup_instructions'            => "🚧 You need to setup a small tool directly on your server now.\r\nPlease follow the setup instructions listed on [GitHub](https://github.com/defichain-api/masternode-health-server/).\r\n\r\nIf you've any questions, please open an issue on GitHub!",
    'buttons'                       => [
        'new_key'               => 'generate a new key for me',
        'existing_key'          => 'Use existing key',
        'existing_key_question' => 'Please enter your DeFi Masternode Health API key now.',
    ],

    'success'   => "✅ Your API key and information service is setup now.",
    'api_key'   => "👾 Please note down your personal API key: `:api_key`",
    'api_error' => '🆘 An API error occured. Please try again later...',
];
