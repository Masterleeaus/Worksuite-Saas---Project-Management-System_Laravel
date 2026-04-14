<?php

/**
 * Module Health Checks for FSMVehicle.
 */
return [
    [
        'id'       => 'fsmvehicle:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmvehicle:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMVehicleServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmvehicle:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMVehicle web routes.',
    ],
    [
        'id'       => 'fsmvehicle:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 4,
        'hint'     => 'At least 4 migration files are expected (vehicles, mileage logs, and order/route links).',
    ],
    [
        'id'       => 'fsmvehicle:vehicle_model',
        'label'    => 'FSMVehicle model present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Models/FSMVehicle.php'),
        'hint'     => 'Models/FSMVehicle.php is required.',
    ],
    [
        'id'       => 'fsmvehicle:mileage_model',
        'label'    => 'FSMVehicleMileageLog model present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Models/FSMVehicleMileageLog.php'),
        'hint'     => 'Models/FSMVehicleMileageLog.php should exist for mileage tracking.',
    ],
];
