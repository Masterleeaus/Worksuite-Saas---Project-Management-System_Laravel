<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('employee_shift_schedules') || !Schema::hasTable('users')) {
            return;
        }

        if (!Schema::hasColumn('employee_shift_schedules', 'company_id')) {
            Schema::table('employee_shift_schedules', function (Blueprint $table) {
                $table->integer('company_id')->unsigned()->nullable()->after('user_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('
                UPDATE employee_shift_schedules
                SET company_id = (
                    SELECT users.company_id
                    FROM users
                    WHERE users.id = employee_shift_schedules.user_id
                )
                WHERE EXISTS (
                    SELECT 1
                    FROM users
                    WHERE users.id = employee_shift_schedules.user_id
                    AND users.company_id IS NOT NULL
                )
            ');
        } else {
            DB::statement('
                UPDATE employee_shift_schedules ess
                INNER JOIN users u ON ess.user_id = u.id
                SET ess.company_id = u.company_id
                WHERE u.company_id IS NOT NULL
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_shift_schedules', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
