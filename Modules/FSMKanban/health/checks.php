<?php
/**
 * Module Health Checks for FSMKanban.
 */
return [
    [
        'id'       => 'fsmkanban:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmkanban:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMKanbanServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmkanban:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMKanban web routes.',
    ],
    [
        'id'       => 'fsmkanban:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'FSMKanban requires at least 1 migration file for fsm_orders kanban fields.',
    ],
    [
        'id'       => 'fsmkanban:settings_entity',
        'label'    => 'FsmKanbanSetting entity present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Entities/FsmKanbanSetting.php'),
        'hint'     => 'Entities/FsmKanbanSetting.php should exist for kanban display configuration.',
    ],
    [
        'id'       => 'fsmkanban:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMKanban requires the FSMCore module to be installed and active.',
    ],
];
