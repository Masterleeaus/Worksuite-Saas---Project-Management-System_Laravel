<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Recurring Order Templates (named presets for recurring configs)
        Schema::create('fsm_recurring_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('frequency_set_id')->nullable()->index();
            $table->unsignedInteger('max_orders')->default(0)->comment('0 = unlimited');
            $table->unsignedBigInteger('fsm_template_id')->nullable()->index()
                ->comment('FSM order template to use for generated orders');
            $table->timestamps();

            $table->foreign('frequency_set_id')->references('id')->on('fsm_frequency_sets')->nullOnDelete();
            $table->foreign('fsm_template_id')->references('id')->on('fsm_templates')->nullOnDelete();
        });

        // Recurring Orders (the repeating schedule)
        Schema::create('fsm_recurrings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name', 64)->unique();
            // State machine: draft → progress → suspend / close
            $table->string('state', 20)->default('draft');
            $table->unsignedBigInteger('recurring_template_id')->nullable()->index();
            $table->unsignedBigInteger('location_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('frequency_set_id')->nullable()->index();
            $table->decimal('scheduled_duration', 8, 2)->nullable()->comment('Hours');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable()->comment('No orders generated after this date');
            $table->unsignedInteger('max_orders')->default(0)->comment('0 = unlimited');
            $table->unsignedBigInteger('fsm_template_id')->nullable()->index()
                ->comment('FSM order template for generated orders');
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->unsignedBigInteger('person_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('recurring_template_id')->references('id')->on('fsm_recurring_templates')->nullOnDelete();
            $table->foreign('location_id')->references('id')->on('fsm_locations')->nullOnDelete();
            $table->foreign('frequency_set_id')->references('id')->on('fsm_frequency_sets')->nullOnDelete();
            $table->foreign('fsm_template_id')->references('id')->on('fsm_templates')->nullOnDelete();
            $table->foreign('team_id')->references('id')->on('fsm_teams')->nullOnDelete();
        });

        // Pivot: recurring ↔ equipment
        Schema::create('fsm_recurring_equipment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fsm_recurring_id');
            $table->unsignedBigInteger('fsm_equipment_id');

            $table->foreign('fsm_recurring_id')->references('id')->on('fsm_recurrings')->cascadeOnDelete();
            $table->foreign('fsm_equipment_id')->references('id')->on('fsm_equipment')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_recurring_equipment');
        Schema::dropIfExists('fsm_recurrings');
        Schema::dropIfExists('fsm_recurring_templates');
    }
};
