<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Communication Automations table — defines trigger → template → action rules.
 * Example: "After booking_created → wait 0 min → send booking_confirmation email".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communication_automations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();

            $table->string('name', 255);

            // Event that fires this automation: booking_created | booking_completed |
            //   booking_cancelled | payment_received | cleaner_assigned | custom
            $table->string('trigger_event', 100)->index();

            // Template to send
            $table->unsignedBigInteger('template_id')->nullable()->index();

            // Minutes to wait after trigger before sending (0 = immediate)
            $table->unsignedInteger('delay_minutes')->default(0);

            // Channel override (leave null to use template's channel)
            $table->string('channel', 30)->nullable();

            // Recipient type: customer | cleaner | admin | custom_email
            $table->string('recipient_type', 50)->default('customer');

            // active | inactive | paused
            $table->string('status', 20)->default('active')->index();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'trigger_event']);
            $table->index(['company_id', 'status']);

            $table->foreign('template_id')
                ->references('id')
                ->on('communication_templates')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communication_automations');
    }
};
