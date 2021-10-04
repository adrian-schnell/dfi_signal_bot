<?php

return [
    'commulated' => [
        'enabled_intro' => "Reward info for all of your *enabled masternodes*:",
        'all_intro'     => "Reward info for all of your *masternodes* (incl. resigned):",

        'sum_minted_blocks' => "\r\nMinted blocks total *:amount blocks*",
        'sum_rewards'       => "\r\nRewards total: *:rewards DFI*",
    ],

    'by_node' => [
        'sum_minted_blocks' => "\r\nMinted blocks: *:amount blocks*",
        'mn_age'            => "\r\nsince :date (in :days days)",
        'day_per_block'     => "\r\n:value days / block",
        'block_per_day'     => "\r\n:value blocks / day",
    ],

    'rewards' => [
        'dfi'         => "*Rewards*:\r\n:dfi DFI",
        'other_coins' => "\r\n≈ :value :ticker",
        'legal'       => "\r\n\r\nDEX prices last update: :date.",
    ],
];
