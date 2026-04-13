<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Structured issue reports submitted by field workers via Titan Go.
 * Separate from job notes so dispatcher can filter/escalate easily.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('nexus_job_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('fsm_order_id')->index();
            $table->unsignedInteger('worker_id')->index();       // users.id (INT)
            $table->string('issue_type', 64);                    // access_blocked|customer_absent|damage|extra_work|safety_risk|equipment_missing
            $table->text('description')->nullable();
            $table->string('status', 32)->default('open');       // open|acknowledged|resolved
            $table->boolean('synced_offline')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();

            $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nexus_job_issues');
    }
};
