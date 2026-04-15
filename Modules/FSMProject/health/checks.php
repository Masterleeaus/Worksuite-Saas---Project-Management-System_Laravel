<?php
/**
 * Module Health Checks for FSMProject.
 */
return [
    [
        'id'       => 'fsmproject:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmproject:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMProjectServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmproject:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMProject web routes.',
    ],
    [
        'id'       => 'fsmproject:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'FSMProject requires at least 1 migration file for project/task linkage fields.',
    ],
    [
        'id'       => 'fsmproject:controller',
        'label'    => 'FsmProjectController present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Http/Controllers/FsmProjectController.php'),
        'hint'     => 'FsmProjectController is required for module routes.',
    ],
    [
        'id'       => 'fsmproject:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMProject requires the FSMCore module to be installed and active.',
    ],
];
