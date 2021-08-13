<?php

return [
    'commulated' => [
        'enabled_intro' => "Reward Info für alle *ENABLED Masternodes*:",
        'all_intro'     => "Reward Info für alle deine *Masternodes* (inkl. RESIGNED):",

        'sum_minted_blocks' => "\r\nMinted gesamt *:amount Blöcke*",
        'sum_rewards'       => "\r\nRewards gesamt: *:rewards DFI*",
    ],

    'by_node' => [
        'sum_minted_blocks' => "\r\nMinted Blöcke: *:amount*",
        'mn_age'            => "\r\nseit dem :date (in :days Tagen)",
        'day_per_block' => "\r\n:value Tage / Block",
        'block_per_day' => "\r\n:value Blöcke / Tag",
    ],
    'rewards' => [
        'dfi'         => "*Rewards*:\r\n:dfi DFI",
        'other_coins' => "\r\n≈ :value :ticker",
        'legal'       => "\r\n\r\nletztes Update DEX Preise: :date.",
    ],
];
