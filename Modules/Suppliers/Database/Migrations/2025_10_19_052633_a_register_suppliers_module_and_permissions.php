<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Register module row (only if modules table exists)
        if (Schema::hasTable('modules')) {
            $hasRow = DB::table('modules')->where('module_name', 'Suppliers')->exists();

            if (!$hasRow) {
                // Keep only guaranteed columns to avoid "unknown column" errors
                DB::table('modules')->insert([
                    'module_name' => 'Suppliers',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        // 2) Add a basic permission (only if permissions table exists)
        if (Schema::hasTable('permissions')) {
            $needsGuard = Schema::hasColumn('permissions', 'guard_name');

            if (!DB::table('permissions')->where('name', 'view_suppliers')->exists()) {
                $row = [
                    'name'       => 'view_suppliers',
                    'display_name' => 'View Suppliers',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if ($needsGuard) {
                    $row['guard_name'] = 'web';
                }
                DB::table('permissions')->insert($row);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->where('name', 'view_suppliers')->delete();
        }
        // Do not delete the modules row in down(); safer for prod.
    }
};