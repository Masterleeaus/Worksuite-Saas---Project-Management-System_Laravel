<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Part 2 – Kanban Card Configuration
 * Per-team toggle settings for which additional fields appear on Kanban cards.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_kanban_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            // Scope: null = global default, team_id = per-team override
            $table->unsignedBigInteger('team_id')->nullable()->index();
            // Toggleable fields
            $table->boolean('show_skills')->default(true);
            $table->boolean('show_stock_status')->default(true);
            $table->boolean('show_vehicle')->default(true);
            $table->boolean('show_timesheet_progress')->default(true);
            $table->boolean('show_warning_overdue')->default(true);
            $table->boolean('show_warning_gps')->default(false);
            $table->boolean('show_warning_photo')->default(false);
            $table->boolean('show_warning_cert')->default(false);
            $table->boolean('show_client_rating')->default(false);
            $table->boolean('show_size')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'team_id'], 'fsm_kanban_configs_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_kanban_configs');
    }
};
