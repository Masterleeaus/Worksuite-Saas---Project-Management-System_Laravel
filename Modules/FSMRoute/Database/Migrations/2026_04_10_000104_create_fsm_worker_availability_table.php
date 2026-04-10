<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_worker_availability', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('person_id')->index();
            $table->date('date')->index();
            $table->boolean('available')->default(false);
            $table->string('reason', 256)->nullable();
            $table->timestamps();

            $table->unique(['person_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_worker_availability');
    }
};
