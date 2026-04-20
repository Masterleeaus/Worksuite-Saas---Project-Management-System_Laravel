<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('complaint')) {
            return;
        }

        Schema::table('complaint', function (Blueprint $table) {
            if (!Schema::hasColumn('complaint', 'qc_record_id')) {
                $table->unsignedBigInteger('qc_record_id')->nullable()->after('quality_control_id');
                $table->index('qc_record_id');
            }

            if (!Schema::hasColumn('complaint', 'site_id')) {
                $table->unsignedBigInteger('site_id')->nullable()->after('job_id');
                $table->index('site_id');
            }

            if (!Schema::hasColumn('complaint', 'team_id')) {
                $table->unsignedBigInteger('team_id')->nullable()->after('site_id');
                $table->index('team_id');
            }
        });
    }

    public function down(): void
    {
        // Non-destructive rollback for production safety.
    }
};
