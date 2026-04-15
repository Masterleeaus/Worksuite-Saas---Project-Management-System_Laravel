<?php
/**
 * Module Health Checks for FSMActivity.
 */
return [
    [
        'id'       => 'fsmactivity:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmactivity:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMActivityServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmactivity:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMActivity web routes.',
    ],
    [
        'id'       => 'fsmactivity:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 2,
        'hint'     => 'FSMActivity requires at least 2 migration files for activity types and activities.',
    ],
    [
        'id'       => 'fsmactivity:activity_model',
        'label'    => 'FSMActivity model present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Models/FSMActivity.php'),
        'hint'     => 'Models/FSMActivity.php is required for activity records.',
    ],
    [
        'id'       => 'fsmactivity:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMActivity requires the FSMCore module to be installed and active.',
    ],
];
