<?php
/**
 * Module Health Checks for FSMAvailability.
 */
return [
    [
        'id'       => 'fsmavailability:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmavailability:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMAvailabilityServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmavailability:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMAvailability web routes.',
    ],
    [
        'id'       => 'fsmavailability:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 2,
        'hint'     => 'At least 2 migration files are required (rules + exceptions tables).',
    ],
    [
        'id'       => 'fsmavailability:service',
        'label'    => 'AvailabilityService present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Services/AvailabilityService.php'),
        'hint'     => 'AvailabilityService.php is required for availability checks.',
    ],
];
