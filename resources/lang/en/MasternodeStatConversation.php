<?php

return [
    'name'               => "*Name*: :name",
    'block_minted_count' => "\r\n*Minted Blocks Count*: :count",
    'last_block'         => "\r\n*Last Block minted*: [:blockHeight](https://mainnet.defichain.io/#/DFI/mainnet/block/:blockHeight)\r\n*Time since last block*: :hours",
    'target_multiplier'  => "\r\n*Target Multiplier*: :multiplier",
    'timelock'           => "\r\n*Locked time*: :timelock",
    'timelock_remaining' => "\r\n*remaining freeze period*: about :locked_until",
    'age'                => "\r\n*Age*: one day|\r\n*Age*: :age days",
    'average_block'      => "\r\n*Average Time*: :average days per block",
    'state'              => "\r\n*Current State*: :state",
];
