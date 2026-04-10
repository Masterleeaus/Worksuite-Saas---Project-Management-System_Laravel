<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gps_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->unique()->index();
            $table->unsignedInteger('location_ping_interval')->default(60)
                ->comment('Seconds between live-tracking pings');
            $table->unsignedInteger('poor_accuracy_threshold')->default(50)
                ->comment('Accuracy (metres) above which check-in is flagged unverified');
            $table->unsignedInteger('default_geofence_radius')->default(200)
                ->comment('Default geofence radius in metres for new job sites');
            $table->unsignedInteger('route_data_retention_days')->default(90)
                ->comment('Days after which route_points rows are purged');
            $table->unsignedInteger('location_data_retention_days')->default(30)
                ->comment('Days after which cleaner_locations rows are purged');
            $table->boolean('route_recording_enabled')->default(true);
            $table->boolean('live_tracking_enabled')->default(true);
            $table->string('map_provider')->default('openstreetmap')
                ->comment('openstreetmap | google');
            $table->string('google_maps_key')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gps_settings');
    }
};
