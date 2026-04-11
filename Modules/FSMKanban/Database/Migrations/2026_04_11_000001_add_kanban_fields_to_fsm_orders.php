<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fieldservice_kanban_info: computed schedule_time_range shown on kanban cards
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('fsm_orders') && ! Schema::hasColumn('fsm_orders', 'schedule_time_range')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                // Stored computed value: "9:00 AM – 11:00 AM" for kanban display
                $table->string('schedule_time_range')->nullable()->after('scheduled_date_end');
            });
        }

        // Kanban display settings per company
        if (! Schema::hasTable('fsm_kanban_settings')) {
            Schema::create('fsm_kanban_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->unique();
                $table->boolean('show_schedule_range')->default(true);
                $table->boolean('show_worker')->default(true);
                $table->boolean('show_location')->default(true);
                $table->boolean('show_priority')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_kanban_settings');

        if (Schema::hasTable('fsm_orders') && Schema::hasColumn('fsm_orders', 'schedule_time_range')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->dropColumn('schedule_time_range');
            });
        }
    }
};
