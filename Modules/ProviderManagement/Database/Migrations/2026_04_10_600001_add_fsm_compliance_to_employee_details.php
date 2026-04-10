<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_details', 'police_check_date')) {
                $table->date('police_check_date')->nullable()->after('about_me');
            }
            if (!Schema::hasColumn('employee_details', 'police_check_expiry')) {
                $table->date('police_check_expiry')->nullable()->after('police_check_date');
            }
            if (!Schema::hasColumn('employee_details', 'insurance_expiry')) {
                $table->date('insurance_expiry')->nullable()->after('police_check_expiry');
            }
            if (!Schema::hasColumn('employee_details', 'wwcc_expiry')) {
                $table->date('wwcc_expiry')->nullable()->after('insurance_expiry');
            }
            if (!Schema::hasColumn('employee_details', 'abn')) {
                $table->string('abn', 20)->nullable()->after('wwcc_expiry');
            }
            if (!Schema::hasColumn('employee_details', 'max_jobs_per_day')) {
                $table->integer('max_jobs_per_day')->default(4)->after('abn');
            }
            if (!Schema::hasColumn('employee_details', 'is_subcontractor')) {
                $table->boolean('is_subcontractor')->default(false)->after('max_jobs_per_day');
            }
            if (!Schema::hasColumn('employee_details', 'fsm_zone_ids')) {
                $table->json('fsm_zone_ids')->nullable()->after('is_subcontractor');
            }
            if (!Schema::hasColumn('employee_details', 'star_rating')) {
                $table->decimal('star_rating', 3, 2)->nullable()->after('fsm_zone_ids');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $cols = ['police_check_date', 'police_check_expiry', 'insurance_expiry', 'wwcc_expiry', 'abn', 'max_jobs_per_day', 'is_subcontractor', 'fsm_zone_ids', 'star_rating'];
            $toDrop = array_filter($cols, fn($col) => Schema::hasColumn('employee_details', $col));
            if (!empty($toDrop)) {
                $table->dropColumn(array_values($toDrop));
            }
        });
    }
};
