<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_availability_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('person_id')->index();
            $table->enum('day_of_week', ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']);
            $table->time('time_start');
            $table->time('time_end');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['person_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_availability_rules');
    }
};
