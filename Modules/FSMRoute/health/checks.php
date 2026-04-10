<?php
/**
 * Module Health Checks for FSMRoute.
 */
return [
    [
        'id'       => 'fsmroute:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmroute:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMRouteServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmroute:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMRoute web routes.',
    ],
    [
        'id'       => 'fsmroute:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 6,
        'hint'     => 'Expected at least 6 migration files for FSMRoute tables.',
    ],
];
