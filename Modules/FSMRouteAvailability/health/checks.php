<?php
/**
 * Module Health Checks for FSMRouteAvailability.
 */
return [
    [
        'id'       => 'fsmrouteavailability:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmrouteavailability:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMRouteAvailabilityServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmrouteavailability:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMRouteAvailability web routes.',
    ],
    [
        'id'       => 'fsmrouteavailability:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 3,
        'hint'     => 'Expected at least 3 migration files for FSMRouteAvailability tables.',
    ],
];
