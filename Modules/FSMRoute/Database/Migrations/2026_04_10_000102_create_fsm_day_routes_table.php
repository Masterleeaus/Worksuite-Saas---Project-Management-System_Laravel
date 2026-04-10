<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_day_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name', 256);
            $table->unsignedBigInteger('route_id')->nullable()->index();
            $table->date('date')->index();
            $table->unsignedBigInteger('person_id')->nullable()->index();
            $table->string('state', 16)->default('draft');
            $table->dateTime('date_start_planned')->nullable();
            $table->float('work_time')->default(8.0);
            $table->float('max_allow_time')->default(10.0);
            $table->timestamps();

            $table->foreign('route_id')->references('id')->on('fsm_routes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_day_routes');
    }
};
