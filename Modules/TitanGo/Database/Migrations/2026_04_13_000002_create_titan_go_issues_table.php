<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * titan_go_issues
 *
 * Structured issue reports submitted from Titan Go workers during a visit.
 * Sent to POST /api/nexus/v1/jobs/{id}/issues
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titan_go_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('worker_id');     // FK → users.id
            $table->unsignedBigInteger('visit_id');   // FK → fsm_orders.id
            $table->string('type');                   // access_blocked|customer_absent|damage|extra_work|safety_risk|equipment_missing
            $table->text('description')->nullable();
            $table->string('status')->default('open'); // open|resolved|escalated
            $table->timestamps();

            $table->index(['company_id', 'visit_id']);
            $table->index('worker_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_go_issues');
    }
};
