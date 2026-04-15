<?php
/**
 * Module Health Checks for FSMStageAction.
 */
return [
    [
        'id'       => 'fsmstageaction:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmstageaction:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMStageActionServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmstageaction:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMStageAction web routes.',
    ],
    [
        'id'       => 'fsmstageaction:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'FSMStageAction requires at least 1 migration for stage action tables.',
    ],
    [
        'id'       => 'fsmstageaction:stage_action_entity',
        'label'    => 'FsmStageAction entity present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Entities/FsmStageAction.php'),
        'hint'     => 'Entities/FsmStageAction.php is required for stage action definitions.',
    ],
    [
        'id'       => 'fsmstageaction:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMStageAction requires the FSMCore module to be installed and active.',
    ],
];
