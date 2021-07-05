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
        'alarm_off' => 'DFI Signal aus',
        'alarm_on'  => 'DFI Signal an',
        'unlink'    => 'trennen',
    ],

    'result' => 'DFI Signal ist aktiv für deine Masternode',
];
