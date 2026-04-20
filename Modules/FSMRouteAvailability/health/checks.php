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
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 4,
        'hint'     => 'FSMRouteAvailability requires at least 4 migration files.',
    ],
    [
        'id'       => 'fsmrouteavailability:fsm_route_dep',
        'label'    => 'FSMRoute dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMRoute\Models\FSMRoute::class),
        'hint'     => 'FSMRouteAvailability requires the FSMRoute module to be installed and active.',
    ],
    [
        'id'       => 'fsmrouteavailability:fsm_availability_dep',
        'label'    => 'FSMAvailability dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMAvailability\Models\FSMAvailabilityRule::class),
        'hint'     => 'FSMRouteAvailability requires the FSMAvailability module to be installed and active.',
    ],
];
