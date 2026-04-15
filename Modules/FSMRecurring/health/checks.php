<?php
/**
 * Module Health Checks for FSMRecurring.
 */
return [
    [
        'id'       => 'fsmrecurring:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmrecurring:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMRecurringServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmrecurring:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMRecurring web routes.',
    ],
    [
        'id'       => 'fsmrecurring:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 3,
        'hint'     => 'FSMRecurring requires at least 3 migration files for recurrence tables and order linkage.',
    ],
    [
        'id'       => 'fsmrecurring:recurring_model',
        'label'    => 'FSMRecurring model present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Models/FSMRecurring.php'),
        'hint'     => 'Models/FSMRecurring.php is required for recurring order generation.',
    ],
    [
        'id'       => 'fsmrecurring:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMRecurring requires the FSMCore module to be installed and active.',
    ],
];
