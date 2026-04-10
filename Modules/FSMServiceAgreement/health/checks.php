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
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 3,
        'hint'     => 'Expected at least 3 migration files for FSMServiceAgreement tables.',
    ],
];
