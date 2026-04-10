<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->decimal('checkin_lat', 10, 8)->nullable()->after('last_updated_by');
            $table->decimal('checkin_lng', 11, 8)->nullable()->after('checkin_lat');
            $table->decimal('checkout_lat', 10, 8)->nullable()->after('checkin_lng');
            $table->decimal('checkout_lng', 11, 8)->nullable()->after('checkout_lat');
            $table->timestamp('checked_in_at')->nullable()->after('checkout_lng');
            $table->timestamp('checked_out_at')->nullable()->after('checked_in_at');
            $table->decimal('geofence_lat', 10, 8)->nullable()->after('checked_out_at');
            $table->decimal('geofence_lng', 11, 8)->nullable()->after('geofence_lat');
            $table->unsignedInteger('geofence_radius')->nullable()->default(200)->after('geofence_lng');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'checkin_lat',
                'checkin_lng',
                'checkout_lat',
                'checkout_lng',
                'checked_in_at',
                'checked_out_at',
                'geofence_lat',
                'geofence_lng',
                'geofence_radius',
            ]);
        });
    }
};
