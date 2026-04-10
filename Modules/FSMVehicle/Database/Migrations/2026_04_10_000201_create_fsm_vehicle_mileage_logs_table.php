<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_vehicle_mileage_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vehicle_id')->index();
            $table->unsignedBigInteger('fsm_order_id')->nullable()->index(); // job this log belongs to
            $table->unsignedBigInteger('logged_by')->nullable()->index();    // user who logged it
            $table->unsignedInteger('odometer_start');   // km at start of trip
            $table->unsignedInteger('odometer_end');     // km at end of trip
            $table->unsignedInteger('km_driven')->default(0); // calculated: end - start
            $table->date('log_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('fsm_vehicles')->cascadeOnDelete();
            $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_vehicle_mileage_logs');
    }
};
