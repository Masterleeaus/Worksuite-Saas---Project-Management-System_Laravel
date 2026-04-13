<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds titan_go_fcm_token column to users table.
 *
 * Used by PUT /api/nexus/v1/worker/fcm-token to register device
 * push notification tokens per worker.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'titan_go_fcm_token')) {
                $table->string('titan_go_fcm_token')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'titan_go_device_id')) {
                $table->string('titan_go_device_id')->nullable()->after('titan_go_fcm_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['titan_go_fcm_token', 'titan_go_device_id']);
        });
    }
};
