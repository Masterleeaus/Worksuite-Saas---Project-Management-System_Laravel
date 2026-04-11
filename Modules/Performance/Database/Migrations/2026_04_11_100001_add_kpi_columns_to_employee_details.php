<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('employee_details')) {
            return;
        }

        Schema::table('employee_details', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_details', 'performance_score')) {
                $table->decimal('performance_score', 5, 2)->default(0.00);
            }
            if (!Schema::hasColumn('employee_details', 'kpi_target_jobs_per_week')) {
                $table->integer('kpi_target_jobs_per_week')->default(0);
            }
            if (!Schema::hasColumn('employee_details', 'kpi_actual_jobs_week')) {
                $table->integer('kpi_actual_jobs_week')->default(0);
            }
            if (!Schema::hasColumn('employee_details', 'kpi_completion_rate')) {
                $table->decimal('kpi_completion_rate', 5, 2)->default(0.00);
            }
            if (!Schema::hasColumn('employee_details', 'kpi_punctuality_rate')) {
                $table->decimal('kpi_punctuality_rate', 5, 2)->default(0.00);
            }
            if (!Schema::hasColumn('employee_details', 'kpi_quality_score')) {
                $table->decimal('kpi_quality_score', 5, 2)->default(0.00);
            }
            if (!Schema::hasColumn('employee_details', 'kpi_complaints_count')) {
                $table->integer('kpi_complaints_count')->default(0);
            }
            if (!Schema::hasColumn('employee_details', 'last_performance_review')) {
                $table->date('last_performance_review')->nullable();
            }
            if (!Schema::hasColumn('employee_details', 'next_performance_review')) {
                $table->date('next_performance_review')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('employee_details')) {
            return;
        }

        Schema::table('employee_details', function (Blueprint $table) {
            $cols = ['performance_score','kpi_target_jobs_per_week','kpi_actual_jobs_week',
                'kpi_completion_rate','kpi_punctuality_rate','kpi_quality_score',
                'kpi_complaints_count','last_performance_review','next_performance_review'];

            foreach ($cols as $col) {
                if (Schema::hasColumn('employee_details', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
