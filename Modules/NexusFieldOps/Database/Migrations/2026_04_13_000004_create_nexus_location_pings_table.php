<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Continuous GPS heartbeat pings from Titan Go workers.
 * Used by Titan Command's live worker map.
 * High-volume — kept lean; auto-partitioned via pruning commands.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('nexus_location_pings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedInteger('worker_id')->index();       // users.id (INT)
            $table->unsignedBigInteger('fsm_order_id')->nullable()->index();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('accuracy', 7, 2)->nullable();       // metres
            $table->string('tracking_mode', 16)->default('foreground'); // foreground|background|heartbeat
            $table->timestamp('pinged_at')->useCurrent();
            $table->index(['company_id', 'worker_id', 'pinged_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nexus_location_pings');
    }
};
