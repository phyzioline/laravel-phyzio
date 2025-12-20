<?php

return [
    'default' => 'EGP',
    'currencies' => [
        'EGP' => [
            'name' => 'Egyptian Pound',
            'symbol' => 'EGP',
            'rate' => 1, // Base
        ],
        'USD' => [
            'name' => 'US Dollar',
            'symbol' => '$',
            'rate' => 0.02, // 1 EGP = 0.02 USD (Approx 50 EGP = 1 USD)
        ],
        'SAR' => [
            'name' => 'Saudi Riyal',
            'symbol' => 'SAR',
            'rate' => 0.075, // 1 EGP = 0.075 SAR
        ],
        'AED' => [
            'name' => 'UAE Dirham',
            'symbol' => 'AED',
            'rate' => 0.073,
        ],
        'KWD' => [
            'name' => 'Kuwaiti Dinar',
            'symbol' => 'KWD',
            'rate' => 0.006,
        ],
    ],
];
