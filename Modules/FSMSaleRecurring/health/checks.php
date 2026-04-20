<?php
/**
 * Module Health Checks for FSMSaleRecurring.
 */
return [
    [
        'id'       => 'fsmsalerecurring:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmsalerecurring:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMSaleRecurringServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmsalerecurring:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMSaleRecurring web routes.',
    ],
    [
        'id'       => 'fsmsalerecurring:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'Expected at least 1 migration file for FSMSaleRecurring.',
    ],
];
