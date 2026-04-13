<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds nexus_fcm_token to the users table.
 *
 * Stores the Firebase Cloud Messaging device token for a field worker's
 * mobile device. Used by the Nexus Field Ops Flutter app to receive
 * push notifications from the dispatcher.
 *
 * The column is nullable; null means the worker has not registered
 * a device token yet (e.g. they have not logged into the mobile app).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nexus_fcm_token', 512)->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nexus_fcm_token');
        });
    }
};
