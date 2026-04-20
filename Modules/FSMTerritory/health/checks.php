<?php
/**
 * Module Health Checks for FSMTerritory.
 */
return [
    [
        'id'       => 'fsmterritory:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmterritory:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMTerritoryServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmterritory:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMTerritory web routes.',
    ],
    [
        'id'       => 'fsmterritory:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 5,
        'hint'     => 'Expected at least 5 migration files for FSMTerritory tables.',
    ],
];
