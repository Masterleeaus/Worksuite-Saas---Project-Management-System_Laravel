<?php
return [
    [
        'id'       => 'fsmsalerecurringagreement:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmsalerecurringagreement:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMSaleRecurringAgreementServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmsalerecurringagreement:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMSaleRecurringAgreement web routes.',
    ],
    [
        'id'       => 'fsmsalerecurringagreement:migration',
        'label'    => 'Migration present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'Expected at least 1 migration file.',
    ],
];
