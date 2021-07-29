<?php

return [
    'name'               => '*Name*: :name',
    'block_minted_count' => '*Minted Blocks Count*: :count',
    'last_block'         => '*Last Block minted*: [:blockHeight](https://mainnet.defichain.io/#/DFI/mainnet/block/:blockHeight)
*Time since last block*: :hours hours',
    'target_multiplier'  => '*Target Multiplier*: :multiplier',
    'timelock'           => '*Locked time*: :timelock',
    'tx_link'            => '*Last Block Tx*: [:txid_truncated](https://mainnet.defichain.io/#/DFI/mainnet/tx/:txid)',
    'age'                => '*Age*: one day|*Age*: :age days',
    'average_block'      => '*Average Time*: :average days per block',
    'state'              => '*Current State*: :state',

    'rewards' => [
        'dfi'         => '*Rewards*:
:dfi DFI',
        'other_coins' => '≈ :value :ticker',
        'legal'       => 'DEX prices last update: :date.',
    ],
];
