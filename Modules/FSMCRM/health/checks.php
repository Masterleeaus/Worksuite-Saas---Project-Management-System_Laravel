<?php
/**
 * Module Health Checks for FSMCRM.
 */
return [
    [
        'id'       => 'fsmcrm:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmcrm:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMCRMServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmcrm:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMCRM web routes.',
    ],
    [
        'id'       => 'fsmcrm:model_lead',
        'label'    => 'FSMLead model present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Models/FSMLead.php'),
        'hint'     => 'Models/FSMLead.php is required.',
    ],
    [
        'id'       => 'fsmcrm:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 2,
        'hint'     => 'At least 2 migration files are expected.',
    ],
];
