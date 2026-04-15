<?php
/**
 * Module Health Checks for FSMSkill.
 */
return [
    [
        'id'       => 'fsmskill:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmskill:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMSkillServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmskill:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMSkill web routes.',
    ],
    [
        'id'       => 'fsmskill:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'FSMSkill requires at least 1 migration for skill tracking tables.',
    ],
    [
        'id'       => 'fsmskill:skill_model',
        'label'    => 'FSMSkill model present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Models/FSMSkill.php'),
        'hint'     => 'Models/FSMSkill.php is required for skill records.',
    ],
    [
        'id'       => 'fsmskill:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMSkill requires the FSMCore module to be installed and active.',
    ],
];
