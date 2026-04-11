<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('project_ratings')) {
            return;
        }

        Schema::table('project_ratings', function (Blueprint $table) {
            if (!Schema::hasColumn('project_ratings', 'booking_id')) {
                $table->unsignedBigInteger('booking_id')->nullable();
            }
            if (!Schema::hasColumn('project_ratings', 'punctuality_rating')) {
                $table->integer('punctuality_rating')->nullable();
            }
            if (!Schema::hasColumn('project_ratings', 'quality_rating')) {
                $table->integer('quality_rating')->nullable();
            }
            if (!Schema::hasColumn('project_ratings', 'attitude_rating')) {
                $table->integer('attitude_rating')->nullable();
            }
            if (!Schema::hasColumn('project_ratings', 'manager_notes')) {
                $table->text('manager_notes')->nullable();
            }
            if (!Schema::hasColumn('project_ratings', 'is_client_rating')) {
                $table->boolean('is_client_rating')->default(false);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('project_ratings')) {
            return;
        }

        Schema::table('project_ratings', function (Blueprint $table) {
            $cols = ['booking_id','punctuality_rating','quality_rating','attitude_rating',
                'manager_notes','is_client_rating'];

            foreach ($cols as $col) {
                if (Schema::hasColumn('project_ratings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
