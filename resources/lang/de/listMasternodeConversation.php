<?php

return [
    'name'          => "*Name*: :name",
    'owner'         => "\r\n\r\n*Owner Address*:\r\n[:owner](https://mainnet.defichain.io/#/DFI/mainnet/address/:owner)",
    'operator'      => "\r\n\r\n*Operator Address*:\r\n:operator",
    'masternode_id' => "\r\n\r\n*Masternode ID*:\r\n[:masternode_id_truncated](https://mainnet.defichain.io/#/DFI/mainnet/tx/:masternode_id)",
    'alarm_on'      => "\r\n\r\n*DFI Signal*: :icon",
    'state'         => "\r\n\r\n*Masternode Status*: :state",
    'synced'        => "\r\n*Synchronisiert (MN Monitor)*: :icon",

    'buttons' => [
        'alarm_off' => "DFI Signal aus",
        'alarm_on'  => "DFI Signal an",
        'unlink'    => "trennen",
    ],

    'result' => "DFI Signal ist aktiv für deine Masternode",

    'no_masternodes_available' => "👾👾👾 *oh no...* 👾👾👾\r\nBisher hast du keine Masternode in DFI Signal eingerichtet.\r\n\r\nKonfiguriere den Bot und starte mit dem /link\_mn oder /sync Befehl.",
];
