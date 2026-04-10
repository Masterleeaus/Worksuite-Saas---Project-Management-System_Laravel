<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name', 256);
            $table->unsignedBigInteger('person_id')->nullable()->index();
            $table->unsignedInteger('max_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('fsm_route_day_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('fsm_route_id');
            $table->unsignedBigInteger('fsm_route_day_id');

            $table->foreign('fsm_route_id')->references('id')->on('fsm_routes')->cascadeOnDelete();
            $table->foreign('fsm_route_day_id')->references('id')->on('fsm_route_days')->cascadeOnDelete();

            $table->primary(['fsm_route_id', 'fsm_route_day_id']);
        });

        Schema::create('fsm_route_location', function (Blueprint $table) {
            $table->unsignedBigInteger('fsm_route_id');
            $table->unsignedBigInteger('fsm_location_id');

            $table->foreign('fsm_route_id')->references('id')->on('fsm_routes')->cascadeOnDelete();
            $table->foreign('fsm_location_id')->references('id')->on('fsm_locations')->cascadeOnDelete();

            $table->primary(['fsm_route_id', 'fsm_location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_route_location');
        Schema::dropIfExists('fsm_route_day_pivot');
        Schema::dropIfExists('fsm_routes');
    }
};
