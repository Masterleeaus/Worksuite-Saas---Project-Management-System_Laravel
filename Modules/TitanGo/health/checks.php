<?php

return [
    'name'   => 'TitanGo',
    'checks' => [

        // Verify all required tables exist
        'database_tables' => function (): array {
            $tables = [
                'titan_go_location_pings',
                'titan_go_issues',
                'titan_go_site_notes',
                'titan_go_worker_statuses',
                'nexus_job_notes',
            ];
            $missing = [];
            foreach ($tables as $t) {
                if (!\Illuminate\Support\Facades\Schema::hasTable($t)) {
                    $missing[] = $t;
                }
            }
            return [
                'status'  => empty($missing) ? 'ok' : 'fail',
                'missing' => $missing,
            ];
        },

        // Verify users columns exist
        'users_columns' => function (): array {
            $ok = \Illuminate\Support\Facades\Schema::hasColumn('users', 'titan_go_fcm_token')
               && \Illuminate\Support\Facades\Schema::hasColumn('users', 'titan_go_device_id');
            return ['status' => $ok ? 'ok' : 'fail'];
        },

        // FSMCore dependency
        'fsmcore_dependency' => function (): array {
            $exists = class_exists(\Modules\FSMCore\Models\FSMOrder::class);
            return [
                'status'  => $exists ? 'ok' : 'fail',
                'message' => $exists ? null : 'FSMCore module not loaded',
            ];
        },
    ],
];
