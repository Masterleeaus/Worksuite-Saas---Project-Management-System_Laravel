<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('appointment_notification_preferences')) {
            return;
        }

        Schema::create('appointment_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();

            $table->boolean('channel_email')->default(true);
            $table->boolean('channel_database')->default(true);

            $table->boolean('notify_assigned')->default(true);
            $table->boolean('notify_reassigned')->default(true);
            $table->boolean('notify_unassigned')->default(true);
            $table->boolean('notify_rescheduled')->default(true);
            $table->boolean('notify_cancelled')->default(true);

            $table->boolean('daily_digest')->default(false);
            $table->string('quiet_hours_start', 5)->nullable(); // HH:MM
            $table->string('quiet_hours_end', 5)->nullable();   // HH:MM

            $table->timestamps();

            $table->unique(['company_id', 'user_id'], 'appt_notif_prefs_company_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_notification_preferences');
    }
};
