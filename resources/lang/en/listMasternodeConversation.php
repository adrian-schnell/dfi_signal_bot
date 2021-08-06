<?php

return [
    'name'          => "*Name*: :name",
    'owner'         => "\r\n\r\n*Owner Address*:\r\n[:owner](https://mainnet.defichain.io/#/DFI/mainnet/address/:owner)",
    'operator'      => "\r\n\r\n*Operator Address*:\r\n:operator",
    'masternode_id' => "\r\n\r\n*Masternode ID*:\r\n[:masternode_id_truncated](https://mainnet.defichain.io/#/DFI/mainnet/tx/:masternode_id)",
    'alarm_on'      => "\r\n\r\n*DFI Signal*: :icon",
    'state'         => "\r\n\r\n*Masternode State*: :state",
    'synced'        => "\r\n*Synchronisiert (MN Monitor)*: :icon",

    'buttons' => [
        'alarm_off' => "DFI signal off",
        'alarm_on'  => "DFI signal on",
        'unlink'    => "unlink",
    ],

    'result' => "Your masternodes are now enabled for DFI Signals.",

    'no_masternodes_available' => "👾👾👾 *oh no...* 👾👾👾\r\nYou don't have any masternodes stored until now.\r\n\r\nStart setting up a masternode with the /link_mn or /sync command",
];
