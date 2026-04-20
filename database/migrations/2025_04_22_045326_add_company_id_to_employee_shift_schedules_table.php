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
        if (!Schema::hasTable('employee_shift_schedules')) {
            return;
        }

        if (!Schema::hasColumn('employee_shift_schedules', 'company_id')) {
            Schema::table('employee_shift_schedules', function (Blueprint $table) {
                $table->integer('company_id')->unsigned()->nullable()->after('user_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (
            !Schema::hasTable('users')
            || !Schema::hasColumn('employee_shift_schedules', 'user_id')
            || !Schema::hasColumn('employee_shift_schedules', 'company_id')
            || !Schema::hasColumn('users', 'company_id')
        ) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('
                UPDATE employee_shift_schedules ess
                INNER JOIN users u ON ess.user_id = u.id
                SET ess.company_id = u.company_id
                WHERE u.company_id IS NOT NULL
            ');
        } else {
            $rows = DB::table('employee_shift_schedules as ess')
                ->join('users as u', 'ess.user_id', '=', 'u.id')
                ->whereNotNull('u.company_id')
                ->select('ess.id', 'u.company_id')
                ->get();

            foreach ($rows as $row) {
                DB::table('employee_shift_schedules')
                    ->where('id', $row->id)
                    ->update(['company_id' => $row->company_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('employee_shift_schedules') && Schema::hasColumn('employee_shift_schedules', 'company_id')) {
            Schema::table('employee_shift_schedules', function (Blueprint $table) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            });
        }
    }
};
