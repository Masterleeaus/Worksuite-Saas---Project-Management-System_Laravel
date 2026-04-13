<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add nexus_fcm_token to users table so Titan Go can register
 * device push tokens per worker, scoped per tenant.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nexus_fcm_token')) {
                $table->string('nexus_fcm_token', 255)->nullable()->after('fcm_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nexus_fcm_token')) {
                $table->dropColumn('nexus_fcm_token');
            }
        });
    }
};
