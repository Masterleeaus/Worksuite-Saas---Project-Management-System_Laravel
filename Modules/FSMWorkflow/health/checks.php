<?php

return [
    [
        'id'       => 'fsmworkflow:migrations',
        'label'    => 'Migrations present',
        'severity' => 'warn',
        'ok'       => count(glob(__DIR__ . '/../Database/Migrations/*.php')) >= 4,
        'hint'     => 'FSMWorkflow requires 4+ migration files.',
    ],
    [
        'id'       => 'fsmworkflow:fsm_core_dep',
        'label'    => 'FSMCore dependency satisfied',
        'severity' => 'error',
        'ok'       => class_exists(\Modules\FSMCore\Models\FSMOrder::class),
        'hint'     => 'FSMWorkflow requires the FSMCore module to be installed and active.',
    ],
    [
        'id'       => 'fsmworkflow:stage_actions_table',
        'label'    => 'fsm_stage_actions table exists',
        'severity' => 'error',
        'ok'       => \Illuminate\Support\Facades\Schema::hasTable('fsm_stage_actions'),
        'hint'     => 'Run migrations: php artisan migrate.',
    ],
    [
        'id'       => 'fsmworkflow:sizes_table',
        'label'    => 'fsm_sizes table exists',
        'severity' => 'error',
        'ok'       => \Illuminate\Support\Facades\Schema::hasTable('fsm_sizes'),
        'hint'     => 'Run migrations: php artisan migrate.',
    ],
    [
        'id'       => 'fsmworkflow:kanban_configs_table',
        'label'    => 'fsm_kanban_configs table exists',
        'severity' => 'error',
        'ok'       => \Illuminate\Support\Facades\Schema::hasTable('fsm_kanban_configs'),
        'hint'     => 'Run migrations: php artisan migrate.',
    ],
    [
        'id'       => 'fsmworkflow:size_columns',
        'label'    => 'size_id / room_count columns on fsm_orders',
        'severity' => 'warn',
        'ok'       => \Illuminate\Support\Facades\Schema::hasColumn('fsm_orders', 'size_id')
                   && \Illuminate\Support\Facades\Schema::hasColumn('fsm_orders', 'room_count'),
        'hint'     => 'Run migrations to add size fields to fsm_orders.',
    ],
];
