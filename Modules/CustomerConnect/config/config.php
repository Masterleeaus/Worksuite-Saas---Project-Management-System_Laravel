<?php

/*
|--------------------------------------------------------------------------
| CustomerConnect Configuration
|--------------------------------------------------------------------------
*/

return [

    // ── Quiet Hours ──────────────────────────────────────────────────────────
    'quiet_hours' => [
        // Uses tenant company timezone. Start and end are inclusive.
        'enabled' => true,
        'start'   => '20:00',
        'end'     => '08:00',
        'days'    => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
    ],

    // ── Daily Sending Caps ───────────────────────────────────────────────────
    'daily_caps' => [
        'enabled'  => true,
        'email'    => 500,
        'sms'      => 200,
        'whatsapp' => 200,
        'telegram' => 500,
    ],

    // ── Send Throttle ────────────────────────────────────────────────────────
    'throttle' => [
        // Spacing between sends per channel (seconds)
        'email'    => 1,
        'sms'      => 2,
        'whatsapp' => 2,
        'telegram' => 1,
    ],

    // ── SLA ──────────────────────────────────────────────────────────────────
    // UPGRADE 6: SLA threshold now in config, not buried in scheduler artisan call.
    'sla' => [
        'default_threshold_minutes' => env('CUSTOMERCONNECT_SLA_MINUTES', 60),
    ],

    // ── Inbox ─────────────────────────────────────────────────────────────────
    'inbox' => [
        'unread_cache_ttl' => env('CUSTOMERCONNECT_UNREAD_CACHE_TTL', 60), // seconds
    ],

    // ── Webhooks ─────────────────────────────────────────────────────────────
    'webhooks' => [
        'twilio' => [
            'validate_signatures' => true,
            'auth_token_env'      => 'TWILIO_AUTH_TOKEN',
        ],
        'vonage' => [
            'validate_signatures'  => false,
            'signature_secret_env' => 'VONAGE_SIGNATURE_SECRET',
        ],
        'telegram' => [
            'secret_token_env' => 'TELEGRAM_WEBHOOK_SECRET',
        ],
    ],

    // ── Exports ───────────────────────────────────────────────────────────────
    'exports' => [
        'max_rows' => 50000,
    ],

    // ── Recipes ───────────────────────────────────────────────────────────────
    'recipes' => [
        'enabled' => true,
    ],

];
