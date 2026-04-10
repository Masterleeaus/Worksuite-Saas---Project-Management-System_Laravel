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
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 8,
        'hint'     => 'Expected at least 8 migration files for FSMCore tables.',
    ],
    [
        'id'       => 'fsmcore:seeders',
        'label'    => 'Seeders present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Database/Seeders/FSMStageSeeder.php'),
        'hint'     => 'FSMStageSeeder is required to seed default stages.',
    ],
];
