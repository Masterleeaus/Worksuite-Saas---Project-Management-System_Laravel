<?php
/**
 * Module Health Checks for SynapseDispatch.
 */
return [
    [
        'id'       => 'synapsedispatch:module_json',
        'label'    => 'module.json present',
        'severity' => 'error',
        'ok'       => file_exists(__DIR__ . '/../module.json'),
        'hint'     => 'module.json is required for module discovery.',
    ],
    [
        'id'       => 'synapsedispatch:service_provider',
        'label'    => 'ServiceProvider present',
        'severity' => 'error',
        'ok'       => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'),
        'hint'     => 'Ensure Providers/SynapseDispatchServiceProvider.php exists.',
    ],
    [
        'id'       => 'synapsedispatch:routes_web',
        'label'    => 'Routes/web.php present',
        'severity' => 'warn',
        'ok'       => file_exists(__DIR__ . '/../Routes/web.php'),
        'hint'     => 'Routes/web.php is required for SynapseDispatch web routes.',
    ],
    [
        'id'       => 'synapsedispatch:migrations',
        'label'    => 'Migrations present (≥5)',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 5,
        'hint'     => 'Expected at least 5 migration files for SynapseDispatch tables.',
    ],
    [
        'id'       => 'synapsedispatch:models',
        'label'    => 'Core models present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Models/Dispatch*.php')) >= 5,
        'hint'     => 'DispatchJob, DispatchWorker, DispatchTeam, DispatchLocation, DispatchEvent models are required.',
    ],
    [
        'id'       => 'synapsedispatch:services',
        'label'    => 'Services present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Services/*.php')) >= 3,
        'hint'     => 'HeuristicPlannerService, JobAssignmentService, WorkerAvailabilityService are required.',
    ],
];
