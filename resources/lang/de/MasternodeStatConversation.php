<?php

return [
    'name'               => '*Name*: :name',
    'block_minted_count' => '*Minted Blocks Anzahl*: :count',
    'last_block'         => '*Letzter Block*: [:blockHeight](https://mainnet.defichain.io/#/DFI/mainnet/block/:blockHeight)
*Letzter Block vor*: :hours Stunden',
    'tx_link'            => '*Transaktion*: [:txid_truncated](https://mainnet.defichain.io/#/DFI/mainnet/tx/:txid)',
    'age'                => '*Alter*: einen Tag|*Alter*: :age Tage',
    'average_block'      => '*Durchschnitt*: :average Tage pro Block',
    'state'              => '*Status*: :state',

    'rewards' => [
        'dfi'         => '*Rewards*:
:dfi DFI',
        'other_coins' => '≈ :value :ticker',
        'legal'       => 'letztes Update DEX Preise: :date.'
    ],
];
