<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shipping Providers Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for various shipping providers like Bosta, Aramex, DHL, etc.
    |
    */

    'providers' => [
        'bosta' => [
            'enabled' => env('BOSTA_ENABLED', false),
            'api_key' => env('BOSTA_API_KEY'),
            'base_url' => env('BOSTA_BASE_URL', 'https://api.bosta.co/api/v2'),
            'webhook_secret' => env('BOSTA_WEBHOOK_SECRET'),
        ],
        
        'aramex' => [
            'enabled' => env('ARAMEX_ENABLED', false),
            'api_key' => env('ARAMEX_API_KEY'),
            'username' => env('ARAMEX_USERNAME'),
            'password' => env('ARAMEX_PASSWORD'),
            'base_url' => env('ARAMEX_BASE_URL', 'https://api.aramex.com'),
            'webhook_secret' => env('ARAMEX_WEBHOOK_SECRET'),
        ],
        
        'dhl' => [
            'enabled' => env('DHL_ENABLED', false),
            'api_key' => env('DHL_API_KEY'),
            'base_url' => env('DHL_BASE_URL', 'https://api.dhl.com'),
            'webhook_secret' => env('DHL_WEBHOOK_SECRET'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Shipping Settings
    |--------------------------------------------------------------------------
    */
    
    'default_provider' => env('DEFAULT_SHIPPING_PROVIDER', 'bosta'),
    'default_method' => env('DEFAULT_SHIPPING_METHOD', 'standard'),
    
    /*
    |--------------------------------------------------------------------------
    | Shipping Cost Calculation
    |--------------------------------------------------------------------------
    */
    
    'base_cost' => env('SHIPPING_BASE_COST', 30), // Base cost in EGP
    'cost_per_kg' => env('SHIPPING_COST_PER_KG', 5), // Cost per kilogram
    
    /*
    |--------------------------------------------------------------------------
    | Shipping Methods
    |--------------------------------------------------------------------------
    */
    
    'methods' => [
        'express' => [
            'name' => 'Express Delivery',
            'multiplier' => 1.5,
            'delivery_days' => 1,
        ],
        'standard' => [
            'name' => 'Standard Delivery',
            'multiplier' => 1.0,
            'delivery_days' => 3,
        ],
        'economy' => [
            'name' => 'Economy Delivery',
            'multiplier' => 0.8,
            'delivery_days' => 5,
        ],
    ],
];

