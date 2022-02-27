<?php

return [
	'name'               => "*Name*: :name",
	'block_minted_count' => "\r\n*Minted Blocks Anzahl*: :count",
	'last_block'         => "\r\n\r\n*Letzter Block*: [:blockHeight](https://mainnet.defichain.io/#/DFI/mainnet/block/:blockHeight)\r\n*Letzter Block vor*: :hours",
	'target_multiplier'  => "\r\n*Multiplikator*: :multiplier",
	'timelock'           => "\r\n*freezed*: :timelock",
	'timelock_remaining' => "\r\n*restlicher Freeze-Zeitraum*: etwa :locked_until",
	'age'                => "\r\n*Alter*: einen Tag|\r\n*Alter*: :age Tage",
	'average_block'      => "\r\n*Durchschnitt*: :average Tage pro Block",
	'state'              => "\r\n*Status*: :state",
];
