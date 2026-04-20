<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_blackout_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('blackout_group_id');
            $table->date('date');
            $table->string('label', 256)->nullable();
            $table->string('zip', 20)->nullable();
            $table->timestamps();

            $table->foreign('blackout_group_id')
                  ->references('id')
                  ->on('fsm_blackout_groups')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_blackout_days');
    }
};
