<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fieldservice_calendar: per-company calendar display preferences
return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('fsm_calendar_settings')) {
            Schema::create('fsm_calendar_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->unique()->index()
                      ->comment('One settings row per company (null = global default)');

                // Default FullCalendar view: dayGridMonth | timeGridWeek | timeGridDay | listWeek
                $table->string('default_view')->default('timeGridWeek');

                // Slot duration for timeline/timeGrid views (HH:MM:SS)
                $table->string('slot_duration')->default('00:30:00');

                // Business hours displayed on the calendar
                $table->string('business_hours_start')->default('07:00');
                $table->string('business_hours_end')->default('20:00');

                // Fallback event colour when a stage has no colour set
                $table->string('default_event_color', 20)->default('#3788d8');

                // Enable the FullCalendar resource-timeline worker view
                $table->boolean('show_resource_view')->default(true);

                // Show weekend columns
                $table->boolean('show_weekends')->default(false);

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_calendar_settings');
    }
};
