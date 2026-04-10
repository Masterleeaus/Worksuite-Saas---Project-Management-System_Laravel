<?php

return [

    /*
    |--------------------------------------------------------------------------
    | App Identity
    |--------------------------------------------------------------------------
    */
    'name'             => env('TITANPWA_APP_NAME', 'CleanSmartOS'),
    'short_name'       => env('TITANPWA_SHORT_NAME', 'CleanSmartOS'),
    'description'      => env('TITANPWA_DESCRIPTION', 'Cleaning Operations Management System — offline-first field ops platform.'),

    /*
    |--------------------------------------------------------------------------
    | PWA Display / Layout
    |--------------------------------------------------------------------------
    */
    'start_url'        => env('TITANPWA_START_URL', '/'),
    'scope'            => env('TITANPWA_SCOPE', '/'),
    'display'          => 'standalone',
    'orientation'      => 'portrait-primary',

    /*
    |--------------------------------------------------------------------------
    | Colours (match your brand palette)
    |--------------------------------------------------------------------------
    */
    'theme_color'      => env('TITANPWA_THEME_COLOR', '#1a5276'),
    'background_color' => env('TITANPWA_BG_COLOR', '#ffffff'),

    /*
    |--------------------------------------------------------------------------
    | Icons
    | Keys are the `sizes` attribute; values are asset paths relative to public/.
    |--------------------------------------------------------------------------
    */
    'icons' => [
        '96x96'   => 'vendor/titanpwa/icons/icon-96x96.png',
        '144x144' => 'vendor/titanpwa/icons/icon-144x144.png',
        '152x152' => 'vendor/titanpwa/icons/icon-152x152.png',
        '192x192' => 'vendor/titanpwa/icons/icon-192x192.png',
        '512x512' => 'vendor/titanpwa/icons/icon-512x512.png',
    ],

    /*
    |--------------------------------------------------------------------------
    | VAPID Keys for Web Push
    | Generate with: php artisan titanpwa:vapid-keys
    |--------------------------------------------------------------------------
    */
    'vapid_public_key'  => env('TITANPWA_VAPID_PUBLIC_KEY', ''),
    'vapid_private_key' => env('TITANPWA_VAPID_PRIVATE_KEY', ''),
    'vapid_subject'     => env('TITANPWA_VAPID_SUBJECT', 'mailto:admin@example.com'),

    /*
    |--------------------------------------------------------------------------
    | Service Worker
    |--------------------------------------------------------------------------
    */
    'sw_path'    => env('TITANPWA_SW_PATH', '/titanpwa-sw.js'),
    'sw_scope'   => env('TITANPWA_SW_SCOPE', '/'),

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    | static_max_age : seconds to cache static assets (JS/CSS/images)
    | dynamic_max_age: seconds to cache dynamic API responses
    */
    'cache' => [
        'static_name'      => 'titanpwa-static-v1',
        'dynamic_name'     => 'titanpwa-dynamic-v1',
        'jobs_name'        => 'titanpwa-jobs-v1',
        'static_max_age'   => 86400,
        'dynamic_max_age'  => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Categories (for manifest)
    |--------------------------------------------------------------------------
    */
    'categories' => ['business', 'utilities'],

    /*
    |--------------------------------------------------------------------------
    | Offline Page Route
    |--------------------------------------------------------------------------
    */
    'offline_url' => '/titanpwa/offline',

];
