<?php
/**
 * Module Health Checks for FSMCalendar.
 */
return [
    [
        'id'       => 'fsmcalendar:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmcalendar:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMCalendarServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmcalendar:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMCalendar web routes.',
    ],
    [
        'id'       => 'fsmcalendar:fsmcore_present',
        'label'    => 'FSMCore module present',
        'severity' => 'error',
        'ok'       => is_dir(__DIR__ . '/../../FSMCore'),
        'hint'     => 'FSMCalendar requires the FSMCore module.',
    ],
    [
        'id'       => 'fsmcalendar:calendar_view',
        'label'    => 'Calendar index view present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Resources/views/calendar/index.blade.php'),
        'hint'     => 'The main calendar Blade view must exist.',
    ],
];
