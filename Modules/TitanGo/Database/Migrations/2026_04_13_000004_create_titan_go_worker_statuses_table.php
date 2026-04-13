<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * titan_go_worker_statuses
 *
 * Quick-action status signals from the worker quick-action bar.
 * Sent to POST /api/nexus/v1/jobs/{id}/status
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titan_go_worker_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('worker_id');     // FK → users.id
            $table->unsignedBigInteger('visit_id');   // FK → fsm_orders.id
            $table->string('status');                 // arrived|running_late|access_issue|need_supplies|job_complete|report_issue
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'visit_id']);
            $table->index('worker_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_go_worker_statuses');
    }
};
