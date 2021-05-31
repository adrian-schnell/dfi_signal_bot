<?php

return [
    'name'               => '*Name*: :name',
    'block_minted_count' => '*Minted Blocks Anzahl*: :count',
    'last_block'         => '*Letzter Block*: [:blockHeight](https://mainnet.defichain.io/#/DFI/mainnet/block/:blockHeight)
*Time since last block*: :hours hours',
    'tx_link'            => '*Transaktion*: [:txid](https://mainnet.defichain.io/#/DFI/mainnet/tx/:txid)',
    'age'                => '*Alter*: einen Tag|*Alter*: :age Tage',
    'average_block'      => '*Durchschnitt*: :average Tage pro Block',
    'state'              => '*Status*: :state',

    'rewards' => [
    'dfi'            => '*Belohnungen*:
:dfi DFI',
    'btc' => '≈ :btc ₿',
    'eth' => '≈ :eth Ξ',
    'usd' => '≈ :usd $',
    'eur' => '≈ :eur €',
    ],
];
