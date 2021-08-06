<?php

return [
    'name'          => '*Name*: :name
',
    'owner'         => '*Owner Address*:
[:owner](https://mainnet.defichain.io/#/DFI/mainnet/address/:owner)
',
    'operator'      => '*Operator Address*:
:operator
',
    'masternode_id' => '*Masternode ID*:
[:masternode_id_truncated](https://mainnet.defichain.io/#/DFI/mainnet/tx/:masternode_id)
',
    'alarm_on'      => '*DFI Signal*: :icon',
    'resigned'      => '*Resigned*: :icon',
    'synced'        => '*Synchronisiert (MN Monitor)*: :icon',

    'buttons' => [
        'alarm_off' => 'DFI signal off',
        'alarm_on'  => 'DFI signal on',
        'unlink'    => 'unlink',
    ],

    'result' => 'Your masternodes are now enabled for DFI Signals.',

    'no_masternodes_available' => "👾👾👾 *oh no...* 👾👾👾\r\nYou don't have any masternodes stored until now.\r\n\r\nStart setting up a masternode with the /link_mn or /sync command",
];
