<?php

return [
    'name' => 'ZeroPay',

    'download_expiry_minutes' => env('ZEROPAY_DOWNLOAD_EXPIRY_MINUTES', 1440),

    'settings_defaults' => [
        'payid_handle' => null,
        'bank_account_name' => null,
        'bank_bsb' => null,
        'bank_account_number' => null,
        'download_expiry_minutes' => 1440,
        'ai_email_enabled' => true,
        'voice_enabled' => false,
        'voice_provider' => 'twilio',
        'voice_max_attempts' => 3,
        'voice_call_window' => '09:00-17:00',
        'voice_do_not_call' => false,
        'enable_payid' => true,
        'enable_bank_transfer' => true,
        'enable_cash' => true,
        'enable_card' => true,
        'enable_paypal' => true,
        'enable_stripe' => true,
        'enable_cryptomus' => false,
        'package_tier' => 'basic',
        'company_overrides' => [],
        'provider_keys' => [
            'twilio' => ['sid' => null, 'token' => null],
            'elevenlabs' => ['api_key' => null],
            'google' => ['api_key' => null],
        ],
    ],

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
