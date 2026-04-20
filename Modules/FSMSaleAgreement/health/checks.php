<?php
return [
    [
        'id'       => 'fsmsaleagreement:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmsaleagreement:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMSaleAgreementServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmsaleagreement:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMSaleAgreement web routes.',
    ],
    [
        'id'       => 'fsmsaleagreement:propagation_service',
        'label'    => 'SaleAgreementPropagationService present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Services/SaleAgreementPropagationService.php'),
        'hint'     => 'The propagation service is the core of this module.',
    ],
];
