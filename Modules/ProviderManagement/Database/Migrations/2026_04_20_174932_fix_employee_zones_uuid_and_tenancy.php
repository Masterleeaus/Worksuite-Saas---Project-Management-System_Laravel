<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('employee_zones')) {
            return;
        }

        if (!Schema::hasColumn('employee_zones', 'company_id')) {
            Schema::table('employee_zones', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->after('id')->index();
            });
        }

        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("
                UPDATE employee_zones ez
                LEFT JOIN users u ON u.id = ez.employee_id
                SET ez.company_id = COALESCE(ez.company_id, u.company_id)
                WHERE ez.company_id IS NULL
            ");
        }

        if ($driver !== 'sqlite' && Schema::hasColumn('employee_zones', 'zone_id')) {
            Schema::table('employee_zones', function (Blueprint $table) {
                $table->string('zone_id', 36)->nullable()->change();
            });
        }

        if (in_array($driver, ['mysql', 'mariadb'], true) && Schema::hasTable('zones')) {
            DB::statement("
                UPDATE employee_zones ez
                LEFT JOIN zones z ON z.id = ez.zone_id
                SET ez.zone_id = NULL
                WHERE ez.zone_id IS NOT NULL AND z.id IS NULL
            ");
        }

        // Rebuild canonical uniqueness with company scope.
        try {
            Schema::table('employee_zones', function (Blueprint $table) {
                $table->dropUnique(['employee_id', 'zone_id']);
            });
        } catch (\Throwable) {
            // Legacy installs may have different index names.
        }

        try {
            Schema::table('employee_zones', function (Blueprint $table) {
                $table->unique(['company_id', 'employee_id', 'zone_id'], 'employee_zones_company_employee_zone_unique');
            });
        } catch (\Throwable) {
            // Best effort in mixed environments.
        }
    }

    public function down(): void
    {
        // non-destructive corrective migration
    }
};
