<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('schedules')) {
            Schema::table('schedules', function (Blueprint $table) {
                // Existing column is user_id; we treat it as schedule-owner / staff.
                if (!Schema::hasColumn('schedules', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->index();
                }

                // Helpful indexes for matching by staff and time.
                $table->index(['user_id', 'date']);
                $table->index(['date', 'start_time', 'end_time']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('schedules')) {
            Schema::table('schedules', function (Blueprint $table) {
                // Default Laravel index names.
                try { $table->dropIndex('schedules_user_id_date_index'); } catch (\Throwable $e) {}
                try { $table->dropIndex('schedules_date_start_time_end_time_index'); } catch (\Throwable $e) {}
            });
        }
    }
};
