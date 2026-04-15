<?php
/**
 * Module Health Checks for FSMRepair.
 */
return [
    [
        'id'       => 'fsmrepair:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmrepair:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMRepairServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmrepair:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMRepair web routes.',
    ],
    [
        'id'       => 'fsmrepair:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'FSMRepair requires at least 1 migration for repair orders.',
    ],
    [
        'id'       => 'fsmrepair:repair_order_entity',
        'label'    => 'FsmRepairOrder entity present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Entities/FsmRepairOrder.php'),
        'hint'     => 'Entities/FsmRepairOrder.php is required for repair order records.',
    ],
    [
        'id'       => 'fsmrepair:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMRepair requires the FSMCore module to be installed and active.',
    ],
];
