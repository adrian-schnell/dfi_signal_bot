<?php

return [
    'intro_local_split'    => "Your debug.log indicates the possibility of local chain split. It is best to check your masternode directly manually.\r\n\r\n",
    'intro_remote_split'   => "A remote split was apparently detected at your node.\r\n\r\n",
    'remote_split_description'   => "Mainnet is :diff blocks *behind your node*!\r\n\r\n",

    'blockdiff'            => "{1}*Blockdiff Mainnet-Local*: :diff block|[2,*]*Blockdiff Mainnet-Local*: :diff blocks",
    'local_block_height'   => "\r\n*current Local Block Height*: :height\r\n",
    'mainnet_block_height' => "*current Mainnet Block Height*: :height\r\n\r\n",
    'local_block_hash'     => "*current Local Block Hash*: :hash\r\n\r\n",
    'mainnet_block_hash'   => "*current Mainnet Block Hash*: :hash\r\n",
    'cooldown_message'     => "`NOTICE: This message will be snoozed for the following :hours hours`",
];
