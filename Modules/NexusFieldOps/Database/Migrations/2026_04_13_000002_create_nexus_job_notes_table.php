<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Job notes submitted by field workers via Titan Go.
 * Linked to an FSM order; kept separately so they survive order archival.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('nexus_job_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('fsm_order_id')->index();
            $table->unsignedInteger('worker_id')->index();     // users.id (INT)
            $table->text('body');
            $table->string('note_type', 32)->default('general'); // general | issue | access | supply
            $table->boolean('synced_offline')->default(false);   // flagged when submitted via offline queue
            $table->timestamps();

            $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nexus_job_notes');
    }
};
