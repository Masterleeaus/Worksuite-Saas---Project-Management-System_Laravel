<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Part 1 – Stage Server Actions
 * Each row represents one automated action that fires when an FSM Order
 * moves to the associated stage.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_stage_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('stage_id')->index();
            $table->string('name', 255)->nullable(); // human-readable label
            $table->string('action_type', 32); // send_sms|send_email|create_activity|create_invoice|webhook|custom
            $table->unsignedBigInteger('template_id')->nullable(); // FK to sms/email template (logical reference)
            $table->unsignedBigInteger('activity_type_id')->nullable(); // FK fsm_activity_types
            $table->string('webhook_url', 2048)->nullable();
            $table->text('condition')->nullable(); // JSON condition blob
            $table->text('custom_payload')->nullable(); // For custom/webhook body
            $table->unsignedInteger('sequence')->default(0)->index();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('stage_id')->references('id')->on('fsm_stages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_stage_actions');
    }
};
