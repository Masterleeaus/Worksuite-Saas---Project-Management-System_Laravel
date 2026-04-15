<?php
/**
 * Module Health Checks for FSMRepairTemplate.
 */
return [
    [
        'id'       => 'fsmrepairtemplate:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmrepairtemplate:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMRepairTemplateServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmrepairtemplate:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMRepairTemplate web routes.',
    ],
    [
        'id'       => 'fsmrepairtemplate:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'FSMRepairTemplate requires at least 1 migration for repair template linkage.',
    ],
    [
        'id'       => 'fsmrepairtemplate:template_entity',
        'label'    => 'FsmRepairOrderTemplate entity present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Entities/FsmRepairOrderTemplate.php'),
        'hint'     => 'Entities/FsmRepairOrderTemplate.php is required for repair template records.',
    ],
    [
        'id'       => 'fsmrepairtemplate:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMRepairTemplate requires the FSMCore module to be installed and active.',
    ],
    [
        'id'       => 'fsmrepairtemplate:fsm_repair_dep',
        'label'    => 'FSMRepair dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMRepair\Entities\FsmRepairOrder::class),
        'hint'     => 'FSMRepairTemplate requires the FSMRepair module to be installed and active.',
    ],
];
