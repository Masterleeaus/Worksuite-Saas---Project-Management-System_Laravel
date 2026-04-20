<?php

return [
    'name' => 'ZeroPay',

    'download_expiry_minutes' => env('ZEROPAY_DOWNLOAD_EXPIRY_MINUTES', 1440),

    'rails' => [
        'zero_fee' => ['payid', 'bank_transfer', 'cash'],
        'processing_fee' => ['card', 'paypal', 'stripe', 'cryptomus'],
    ],

    'callbacks' => [
        'stripe_secret' => env('ZEROPAY_STRIPE_WEBHOOK_SECRET', ''),
        'paypal_secret' => env('ZEROPAY_PAYPAL_WEBHOOK_SECRET', ''),
        'cryptomus_secret' => env('ZEROPAY_CRYPTOMUS_WEBHOOK_SECRET', ''),
    ],

    'voice' => [
        'provider' => env('ZEROPAY_VOICE_PROVIDER', 'twilio'),
        'enabled' => (bool) env('ZEROPAY_VOICE_ENABLED', false),
    ],
];
