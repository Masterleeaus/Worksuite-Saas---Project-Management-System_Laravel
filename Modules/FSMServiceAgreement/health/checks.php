<?php
/**
 * Module Health Checks for FSMServiceAgreement.
 */
return [
    [
        'id'       => 'fsmserviceagreement:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmserviceagreement:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMServiceAgreementServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmserviceagreement:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMServiceAgreement web routes.',
    ],
    [
        'id'       => 'fsmserviceagreement:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 4,
        'hint'     => 'Expected at least 4 migration files for FSMServiceAgreement tables (incl. permissions).',
    ],
    [
        'id'       => 'fsmserviceagreement:generate_command',
        'label'    => 'GenerateAgreementOrdersCommand present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Console/Commands/GenerateAgreementOrdersCommand.php'),
        'hint'     => 'GenerateAgreementOrdersCommand (fsm:generate-agreement-orders) drives recurring order automation.',
    ],
];
