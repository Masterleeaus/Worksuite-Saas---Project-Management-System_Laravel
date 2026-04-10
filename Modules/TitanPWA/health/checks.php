<?php

/**
 * TitanPWA Module Health Checks
 *
 * Returns a list of checks that a Doctor tool (or humans) can run to verify
 * the module is correctly set up.
 */
return [
    [
        'id'       => 'titanpwa:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'titanpwa:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Providers/TitanPWAServiceProvider.php'),
        'hint'     => 'Providers/TitanPWAServiceProvider.php must exist.',
    ],
    [
        'id'       => 'titanpwa:service_worker',
        'label'    => 'Service worker source present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Resources/js/titanpwa-sw.js'),
        'hint'     => 'Resources/js/titanpwa-sw.js must exist. Run: php artisan vendor:publish --tag=titanpwa-sw',
    ],
    [
        'id'       => 'titanpwa:sw_published',
        'label'    => 'Service worker published to public/',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../../../../public/titanpwa-sw.js'),
        'hint'     => 'Run: php artisan vendor:publish --tag=titanpwa-sw',
    ],
    [
        'id'       => 'titanpwa:icons_published',
        'label'    => 'Icons published to public/vendor/titanpwa/',
        'severity' => 'warn',
        'ok'       => is_dir(__DIR__ . '/../../../../public/vendor/titanpwa/icons'),
        'hint'     => 'Run: php artisan vendor:publish --tag=titanpwa-icons',
    ],
    [
        'id'       => 'titanpwa:vapid_public',
        'label'    => 'VAPID public key configured',
        'severity' => 'warn',
        'ok'       => ! empty(env('TITANPWA_VAPID_PUBLIC_KEY')),
        'hint'     => 'Run: php artisan titanpwa:vapid-keys',
    ],
    [
        'id'       => 'titanpwa:vapid_private',
        'label'    => 'VAPID private key configured',
        'severity' => 'warn',
        'ok'       => ! empty(env('TITANPWA_VAPID_PRIVATE_KEY')),
        'hint'     => 'Run: php artisan titanpwa:vapid-keys',
    ],
    [
        'id'       => 'titanpwa:migrations',
        'label'    => 'Migrations directory present',
        'severity' => 'warn',
        'ok'       => is_dir(__DIR__ . '/../Database/Migrations'),
        'hint'     => 'Database/Migrations directory should exist.',
    ],
    [
        'id'       => 'titanpwa:offline_html',
        'label'    => 'Offline fallback page published',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../../../../public/offline.html'),
        'hint'     => 'Run: php artisan vendor:publish --tag=titanpwa-offline',
    ],
];
