<?php
/**
 * Module Health Checks for FSMPortal.
 */
return [
    [
        'id'       => 'fsmportal:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'fsmportal:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/FSMPortalServiceProvider.php exists.',
    ],
    [
        'id'       => 'fsmportal:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for FSMPortal web routes.',
    ],
    [
        'id'       => 'fsmportal:migration',
        'label'    => 'Reclean requests migration present',
        'severity' => 'warn',
        'ok'       => (bool) glob(__DIR__ . '/../Database/Migrations/*reclean_requests*'),
        'hint'     => 'Migration for fsm_portal_reclean_requests table should be present.',
    ],
    [
        'id'       => 'fsmportal:views',
        'label'    => 'Portal views present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Resources/views/portal/jobs/index.blade.php'),
        'hint'     => 'Portal job list view is required.',
    ],
    [
        'id'       => 'fsmcore:dependency',
        'label'    => 'FSMCore dependency available',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMPortal requires the FSMCore module to be installed and loaded.',
    ],
];
