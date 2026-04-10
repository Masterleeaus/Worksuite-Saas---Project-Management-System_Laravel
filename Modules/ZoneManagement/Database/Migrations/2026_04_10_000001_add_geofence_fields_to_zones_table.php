<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zones', function (Blueprint $table) {
            if (!Schema::hasColumn('zones', 'zone_type')) {
                $table->enum('zone_type', ['polygon', 'circle'])->default('polygon')->after('is_active');
            }
            if (!Schema::hasColumn('zones', 'center_lat')) {
                $table->decimal('center_lat', 10, 8)->nullable()->after('zone_type');
            }
            if (!Schema::hasColumn('zones', 'center_lng')) {
                $table->decimal('center_lng', 11, 8)->nullable()->after('center_lat');
            }
            if (!Schema::hasColumn('zones', 'radius')) {
                $table->unsignedInteger('radius')->default(200)->after('center_lng')
                    ->comment('Geofence radius in metres (default 200m)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('zones', function (Blueprint $table) {
            $table->dropColumn(['zone_type', 'center_lat', 'center_lng', 'radius']);
        });
    }
};
