<?php
/**
 * Module Health Checks for FSMTimesheet.
 */
return [
    [
        'id'       => 'fsmtimesheet:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmtimesheet:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMTimesheetServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmtimesheet:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMTimesheet web routes.',
    ],
    [
        'id'       => 'fsmtimesheet:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 1,
        'hint'     => 'FSMTimesheet requires at least 1 migration for timesheet lines.',
    ],
    [
        'id'       => 'fsmtimesheet:timesheet_line_model',
        'label'    => 'FSMTimesheetLine model present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../Models/FSMTimesheetLine.php'),
        'hint'     => 'Models/FSMTimesheetLine.php is required for logged work-hour records.',
    ],
    [
        'id'       => 'fsmtimesheet:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMTimesheet requires the FSMCore module to be installed and active.',
    ],
];
