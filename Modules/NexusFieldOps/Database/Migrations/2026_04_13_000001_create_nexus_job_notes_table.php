<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the nexus_job_notes table.
 *
 * Stores free-text notes that field workers attach to FSM work orders
 * from the Nexus Field Ops mobile app. Notes are visible to dispatchers
 * in the CleanSmartOS back-office.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nexus_job_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fsm_order_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->text('body');
            $table->timestamps();

            $table->foreign('fsm_order_id')
                ->references('id')->on('fsm_orders')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nexus_job_notes');
    }
};
