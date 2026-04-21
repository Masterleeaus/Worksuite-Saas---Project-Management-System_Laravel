<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create booking_reminder_logs
 *
 * Provides DB-level idempotency for reminder dispatch so that the same
 * reminder (schedule + lead time + trigger) is never sent more than once,
 * even if a queue job is retried or multiple processes run concurrently.
 *
 * Unique key: (schedule_id, lead_minutes, trigger)
 *   - 'scheduled' → cron-based upcoming-booking scan
 *   - 'assign'    → first assignment trigger
 *   - 'reschedule'→ reassignment / reschedule trigger
 *
 * Safe to run on both fresh installs and existing databases.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_reminder_logs')) {
            return;
        }

        Schema::create('booking_reminder_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('company_id')->default(0);
            $table->unsignedInteger('lead_minutes');
            $table->string('trigger', 20)->default('scheduled');
            $table->timestamp('sent_at')->useCurrent();

            // Primary dedup key — one send per schedule × lead_time × trigger.
            $table->unique(['schedule_id', 'lead_minutes', 'trigger'], 'uq_booking_reminder_log');

            $table->index('schedule_id');
            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_reminder_logs');
    }
};
