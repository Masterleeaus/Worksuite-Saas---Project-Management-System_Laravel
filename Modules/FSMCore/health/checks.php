<?php
/**
 * Module Health Checks for FSMCore.
 */
return [
    [
        'id'       => 'fsmcore:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmcore:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMCoreServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmcore:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSM web routes.',
    ],
    [
        'id'       => 'fsmcore:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 9,
        'hint'     => 'Expected at least 9 migration files for FSMCore tables.',
    ],
    [
        'id'       => 'fsmcore:seeders',
        'label'    => 'Seeders present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Database/Seeders/FSMStageSeeder.php'),
        'hint'     => 'FSMStageSeeder is required to seed default stages.',
    ],
    [
        'id'       => 'fsmcore:routes_api',
        'label'    => 'Routes/api.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/api.php'),
        'hint'     => 'Routes/api.php is required for the FSM mobile worker REST API.',
    ],
    [
        'id'       => 'fsmcore:worker_auth_controller',
        'label'    => 'WorkerAuthController present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Http/Controllers/Api/WorkerAuthController.php'),
        'hint'     => 'WorkerAuthController provides login/logout for field-worker mobile apps.',
    ],
];
