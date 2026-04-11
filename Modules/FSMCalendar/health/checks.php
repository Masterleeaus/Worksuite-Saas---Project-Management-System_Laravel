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
        'id'       => 'fsmcalendar:routes_api',
        'label'    => 'Routes/api.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/api.php'),
        'hint'     => 'Routes/api.php is required for FSMCalendar API routes.',
    ],
    [
        'id'       => 'fsmcalendar:migrations',
        'label'    => 'Database/Migrations directory present',
        'severity' => 'warn',
        'ok'       => is_dir(__DIR__ . '/../Database/Migrations'),
        'hint'     => 'Database/Migrations is required for schema management.',
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
    [
        'id'       => 'fsmcalendar:settings_table',
        'label'    => 'fsm_calendar_settings table exists',
        'severity' => 'warn',
        'ok'       => (function () {
            try {
                return \Illuminate\Support\Facades\Schema::hasTable('fsm_calendar_settings');
            } catch (\Throwable $e) {
                return false;
            }
        })(),
        'hint'     => 'Run migrations: php artisan module:migrate FSMCalendar',
    ],
];
