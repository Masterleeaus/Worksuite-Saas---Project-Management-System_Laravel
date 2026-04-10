<?php

return [
    'twilio' => [
        'account_sid'          => env('TITANREACH_TWILIO_ACCOUNT_SID',  env('TITANHELLO_TWILIO_ACCOUNT_SID')),
        'auth_token'           => env('TITANREACH_TWILIO_AUTH_TOKEN',   env('TITANHELLO_TWILIO_AUTH_TOKEN')),
        'from_sms_number'      => env('TITANREACH_TWILIO_FROM_SMS_NUMBER', env('TITANHELLO_TWILIO_FROM_NUMBER')),
        'from_whatsapp_number' => env('TITANREACH_TWILIO_FROM_WHATSAPP_NUMBER'),
        'base_url'             => env('TITANREACH_TWILIO_BASE_URL', 'https://api.twilio.com/2010-04-01/Accounts'),
    ],

    'telegram' => [
        'bot_token' => env('TITANREACH_TELEGRAM_BOT_TOKEN'),
    ],

    'ai' => [
        'enabled'          => (bool) env('TITANREACH_AI_ENABLED', false),
        'gateway_endpoint' => env('TITANREACH_AI_GATEWAY_ENDPOINT', ''),
    ],

    'webhooks' => [
        'require_signature' => (bool) env('TITANREACH_WEBHOOKS_REQUIRE_SIGNATURE', false),
    ],
];
