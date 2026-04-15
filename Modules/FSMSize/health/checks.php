<?php
/**
 * Module Health Checks for FSMSize.
 */
return [
    [
        'id'       => 'fsmsize:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmsize:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMSizeServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmsize:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMSize web routes.',
    ],
    [
        'id'       => 'fsmsize:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'FSMSize requires at least 1 migration for size management tables.',
    ],
    [
        'id'       => 'fsmsize:size_entity',
        'label'    => 'FsmSize entity present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Entities/FsmSize.php'),
        'hint'     => 'Entities/FsmSize.php is required for size definitions.',
    ],
    [
        'id'       => 'fsmsize:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMSize requires the FSMCore module to be installed and active.',
    ],
];
