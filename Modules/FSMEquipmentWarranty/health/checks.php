<?php
return [
    [
        'id'       => 'fsmequipmentwarranty:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmequipmentwarranty:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMEquipmentWarrantyServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmequipmentwarranty:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMEquipmentWarranty web routes.',
    ],
    [
        'id'       => 'fsmequipmentwarranty:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 2,
        'hint'     => 'Expected at least 2 migration files.',
    ],
];
