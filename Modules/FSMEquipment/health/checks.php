<?php
/**
 * Module Health Checks for FSMEquipment.
 */
return [
    [
        'id'       => 'fsmequipment:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmequipment:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMEquipmentServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmequipment:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMEquipment web routes.',
    ],
    [
        'id'       => 'fsmequipment:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 3,
        'hint'     => 'FSMEquipment requires at least 3 migration files for repair templates/orders and warranties.',
    ],
    [
        'id'       => 'fsmequipment:repair_order_model',
        'label'    => 'RepairOrder model present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Models/RepairOrder.php'),
        'hint'     => 'Models/RepairOrder.php is required for repair order management.',
    ],
    [
        'id'       => 'fsmequipment:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMEquipment requires the FSMCore module to be installed and active.',
    ],
];
