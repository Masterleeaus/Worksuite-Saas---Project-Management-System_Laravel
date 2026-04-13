<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }
        
        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'checkin_lat')) {
                $table->decimal('checkin_lat', 10, 8)->nullable()->after('last_updated_by');
            }
            if (! Schema::hasColumn('tasks', 'checkin_lng')) {
                $table->decimal('checkin_lng', 11, 8)->nullable()->after('checkin_lat');
            }
            if (! Schema::hasColumn('tasks', 'checkout_lat')) {
                $table->decimal('checkout_lat', 10, 8)->nullable()->after('checkin_lng');
            }
            if (! Schema::hasColumn('tasks', 'checkout_lng')) {
                $table->decimal('checkout_lng', 11, 8)->nullable()->after('checkout_lat');
            }
            if (! Schema::hasColumn('tasks', 'checked_in_at')) {
                $table->timestamp('checked_in_at')->nullable()->after('checkout_lng');
            }
            if (! Schema::hasColumn('tasks', 'checked_out_at')) {
                $table->timestamp('checked_out_at')->nullable()->after('checked_in_at');
            }
            if (! Schema::hasColumn('tasks', 'geofence_lat')) {
                $table->decimal('geofence_lat', 10, 8)->nullable()->after('checked_out_at');
            }
            if (! Schema::hasColumn('tasks', 'geofence_lng')) {
                $table->decimal('geofence_lng', 11, 8)->nullable()->after('geofence_lat');
            }
            if (! Schema::hasColumn('tasks', 'geofence_radius')) {
                $table->unsignedInteger('geofence_radius')->nullable()->default(200)->after('geofence_lng');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }
        
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
