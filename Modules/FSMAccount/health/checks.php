<?php
/**
 * Module Health Checks for FSMAccount.
 */
return [
    [
        'id'       => 'fsmaccount:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmaccount:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMAccountServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmaccount:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMAccount web routes.',
    ],
    [
        'id'       => 'fsmaccount:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'FSMAccount requires at least 1 migration file for billing links.',
    ],
    [
        'id'       => 'fsmaccount:controller',
        'label'    => 'FsmAccountController present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Http/Controllers/FsmAccountController.php'),
        'hint'     => 'FsmAccountController is required for module routes.',
    ],
    [
        'id'       => 'fsmaccount:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMAccount requires the FSMCore module to be installed and active.',
    ],
];
