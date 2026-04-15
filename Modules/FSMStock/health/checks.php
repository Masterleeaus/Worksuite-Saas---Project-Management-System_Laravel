<?php
/**
 * Module Health Checks for FSMStock.
 */
return [
    [
        'id'       => 'fsmstock:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmstock:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMStockServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmstock:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMStock web routes.',
    ],
    [
        'id'       => 'fsmstock:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'FSMStock requires at least 1 migration for stock and movement tables.',
    ],
    [
        'id'       => 'fsmstock:stock_item_model',
        'label'    => 'FSMStockItem model present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Models/FSMStockItem.php'),
        'hint'     => 'Models/FSMStockItem.php is required for stock catalog records.',
    ],
    [
        'id'       => 'fsmstock:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMStock requires the FSMCore module to be installed and active.',
    ],
];
