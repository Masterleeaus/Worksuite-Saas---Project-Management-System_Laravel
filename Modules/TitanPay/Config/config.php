<?php

return [
    'name' => 'TitanPay',

    /*
    |--------------------------------------------------------------------------
    | Cryptomus Settings
    |--------------------------------------------------------------------------
    */
    'cryptomus' => [
        'payment_api_key' => env('CRYPTOMUS_PAYMENT_API_KEY', ''),
        'merchant_uuid'   => env('CRYPTOMUS_MERCHANT_UUID', ''),
        'webhook_secret'  => env('CRYPTOMUS_WEBHOOK_SECRET', ''),
        'currency'        => env('CRYPTOMUS_CURRENCY', 'USD'),
        'network'         => env('CRYPTOMUS_NETWORK', ''),
        'is_active'       => env('CRYPTOMUS_ACTIVE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Link Settings
    |--------------------------------------------------------------------------
    */
    'payment_link' => [
        'expiry_days' => env('TITANPAY_LINK_EXPIRY_DAYS', 30),
        'sign_secret' => env('TITANPAY_LINK_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Settings
    |--------------------------------------------------------------------------
    */
    'qr' => [
        'expiry_days' => env('TITANPAY_QR_EXPIRY_DAYS', 7),
        'size'        => 200,
    ],
];
