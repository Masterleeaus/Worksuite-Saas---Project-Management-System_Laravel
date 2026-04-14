<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * titan_go_location_pings
 *
 * Records continuous GPS heartbeats from Titan Go workers.
 * Sent to POST /api/fsm/v1/location/ping
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('titan_go_location_pings')) {
            return;
        }
        
        Schema::create('titan_go_location_pings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('worker_id');     // FK → users.id
            $table->unsignedBigInteger('visit_id')->nullable(); // FK → fsm_orders.id
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->float('accuracy')->nullable();
            $table->string('tracking_mode')->default('foreground'); // foreground|background
            $table->timestamps();

            $table->index(['company_id', 'worker_id', 'created_at']);
            $table->index('visit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_go_location_pings');
    }
};
