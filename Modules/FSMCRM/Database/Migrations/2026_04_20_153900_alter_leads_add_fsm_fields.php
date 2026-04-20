<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('leads')) {
            return;
        }

        Schema::table('leads', function (Blueprint $table) {
            if (! Schema::hasColumn('leads', 'fsm_location_id')) {
                $table->unsignedBigInteger('fsm_location_id')->nullable();
            }

            if (! Schema::hasColumn('leads', 'fsm_service_type_id')) {
                $table->unsignedBigInteger('fsm_service_type_id')->nullable();
            }

            if (! Schema::hasColumn('leads', 'site_count')) {
                $table->integer('site_count')->nullable();
            }

            if (! Schema::hasColumn('leads', 'estimated_hours')) {
                $table->decimal('estimated_hours', 8, 2)->nullable();
            }

            if (! Schema::hasColumn('leads', 'create_recurring')) {
                $table->boolean('create_recurring')->default(false);
            }
        });

        $this->addForeignKeyIfMissing('leads', 'fsm_location_id', 'fsm_locations');
        $this->addForeignKeyIfMissing('leads', 'fsm_service_type_id', 'fsm_templates');

        if (Schema::hasTable('fsm_leads')) {
            $this->migrateFsmLeadsToNativeLeads();
            $this->dropFsmOrdersLeadForeignKey();
            Schema::drop('fsm_leads');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('leads')) {
            return;
        }

        try {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropForeign(['fsm_location_id']);
            });
        } catch (\Throwable) {
        }

        try {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropForeign(['fsm_service_type_id']);
            });
        } catch (\Throwable) {
        }

        Schema::table('leads', function (Blueprint $table) {
            $dropColumns = [];

            foreach (['fsm_location_id', 'fsm_service_type_id', 'site_count', 'estimated_hours', 'create_recurring'] as $column) {
                if (Schema::hasColumn('leads', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if (! empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }

    private function addForeignKeyIfMissing(string $tableName, string $column, string $targetTable): void
    {
        if (! Schema::hasTable($targetTable) || ! Schema::hasColumn($tableName, $column)) {
            return;
        }

        try {
            Schema::table($tableName, function (Blueprint $table) use ($column, $targetTable) {
                $table->foreign($column)->references('id')->on($targetTable)->nullOnDelete();
            });
        } catch (\Throwable) {
            // FK already exists or is unsupported by the current connection.
        }
    }

    private function migrateFsmLeadsToNativeLeads(): void
    {
        $statusIds = [
            'new' => $this->resolveLeadStatusId('new', '#6c757d'),
            'qualified' => $this->resolveLeadStatusId('qualified', '#0dcaf0'),
            'won' => $this->resolveLeadStatusId('won', '#198754'),
            'lost' => $this->resolveLeadStatusId('lost', '#dc3545'),
        ];

        $now = Carbon::now();
        $columnPriority = (int) DB::table('leads')->max('column_priority');
        $leadIdMap = [];

        DB::table('fsm_leads')
            ->orderBy('id')
            ->get()
            ->each(function ($fsmLead) use (&$columnPriority, &$leadIdMap, $statusIds, $now) {
                $columnPriority++;
                $stage = strtolower((string) ($fsmLead->stage ?? 'new'));

                $leadIdMap[$fsmLead->id] = DB::table('leads')->insertGetId([
                    'company_id' => $fsmLead->company_id,
                    'client_id' => $fsmLead->partner_id,
                    'status_id' => $statusIds[$stage] ?? $statusIds['new'],
                    'column_priority' => $columnPriority,
                    'company_name' => $fsmLead->name,
                    'client_name' => $fsmLead->contact_name ?: $fsmLead->name,
                    'client_email' => $fsmLead->email,
                    'mobile' => $fsmLead->phone,
                    'note' => $fsmLead->notes,
                    'next_follow_up' => 'yes',
                    'value' => $fsmLead->expected_revenue ?? 0,
                    'fsm_location_id' => $fsmLead->fsm_location_id,
                    'fsm_service_type_id' => $fsmLead->service_type_id,
                    'site_count' => $fsmLead->site_count,
                    'estimated_hours' => $fsmLead->estimated_hours,
                    'create_recurring' => (bool) ($fsmLead->create_recurring ?? false),
                    'created_at' => $fsmLead->created_at ?? $now,
                    'updated_at' => $fsmLead->updated_at ?? $now,
                ]);
            });

        if (Schema::hasTable('fsm_orders') && Schema::hasColumn('fsm_orders', 'lead_id')) {
            foreach ($leadIdMap as $oldLeadId => $newLeadId) {
                DB::table('fsm_orders')
                    ->where('lead_id', $oldLeadId)
                    ->update(['lead_id' => $newLeadId]);
            }
        }
    }

    private function resolveLeadStatusId(string $type, string $labelColor): int
    {
        $status = DB::table('lead_status')->where('type', $type)->first();

        if ($status) {
            return (int) $status->id;
        }

        return (int) DB::table('lead_status')->insertGetId([
            'type' => $type,
            'priority' => 0,
            'default' => 0,
            'label_color' => $labelColor,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    private function dropFsmOrdersLeadForeignKey(): void
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasColumn('fsm_orders', 'lead_id')) {
            return;
        }

        try {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->dropForeign(['lead_id']);
            });
        } catch (\Throwable) {
            // No FK exists yet.
        }
    }
};
