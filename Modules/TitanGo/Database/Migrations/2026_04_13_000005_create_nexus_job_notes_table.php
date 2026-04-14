<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * nexus_job_notes
 *
 * Field notes attached to visits (FSM orders), submitted by workers
 * via Titan Go.  POST /api/nexus/v1/jobs/{id}/notes
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('nexus_job_notes')) {
            Schema::create('nexus_job_notes', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id');
                $table->unsignedBigInteger('fsm_order_id');   // FK → fsm_orders.id
                $table->unsignedInteger('worker_id');         // FK → users.id
                $table->text('body');
                $table->timestamps();

                $table->index(['company_id', 'fsm_order_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('nexus_job_notes');
    }
};
