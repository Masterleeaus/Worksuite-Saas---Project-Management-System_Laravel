<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name', 128);                      // e.g. "Van 03 - ABC 123"
            $table->string('license_plate', 32)->nullable();  // Registration plate
            $table->string('make', 64)->nullable();           // Vehicle make
            $table->string('model', 64)->nullable();          // Vehicle model
            $table->unsignedSmallInteger('year')->nullable(); // Year of manufacture
            $table->string('vin', 64)->nullable();            // VIN number
            $table->unsignedBigInteger('person_id')->nullable()->index(); // Primary assigned driver
            $table->unsignedInteger('current_mileage')->default(0);   // Current odometer (km)
            $table->date('last_service_date')->nullable();
            $table->unsignedInteger('next_service_mileage')->nullable(); // Trigger km for next service
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_vehicles');
    }
};
