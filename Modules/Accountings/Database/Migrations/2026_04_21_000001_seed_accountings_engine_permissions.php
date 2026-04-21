<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('permissions') || !Schema::hasTable('modules')) {
            return;
        }

        $module = DB::table('modules')->where('module_name', 'accountings')->first();
        $moduleId = $module ? $module->id : DB::table('modules')->insertGetId([
            'module_name' => 'accountings',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $hasGuardName = Schema::hasColumn('permissions', 'guard_name');

        foreach (['view_accounts','post_transactions','adjust_invoices','approve_writeoffs','close_periods','view_profitability_reports','export_financial_reports'] as $permission) {
            if (DB::table('permissions')->where('name', $permission)->exists()) {
                continue;
            }

            $row = [
                'name' => $permission,
                'module_id' => $moduleId,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($hasGuardName) {
                $row['guard_name'] = 'web';
            }

            DB::table('permissions')->insert($row);
        }
    }

    public function down(): void
    {
    }
};
