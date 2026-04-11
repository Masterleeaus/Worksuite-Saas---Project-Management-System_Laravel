<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fieldservice_project: link FSM orders to Projects / Tasks
return new class extends Migration {
    public function up(): void
    {
        // fsm_orders gets project_id + task_id
        if (Schema::hasTable('fsm_orders')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                if (! Schema::hasColumn('fsm_orders', 'project_id')) {
                    $table->unsignedBigInteger('project_id')->nullable()->after('template_id');
                }
                if (! Schema::hasColumn('fsm_orders', 'task_id')) {
                    $table->unsignedBigInteger('task_id')->nullable()->after('project_id');
                }
            });
        }

        // fsm_teams gets a default project
        if (Schema::hasTable('fsm_teams') && ! Schema::hasColumn('fsm_teams', 'project_id')) {
            Schema::table('fsm_teams', function (Blueprint $table) {
                $table->unsignedBigInteger('project_id')->nullable()->after('team_leader_id');
            });
        }

        // fsm_locations gets project linkage
        if (Schema::hasTable('fsm_locations') && ! Schema::hasColumn('fsm_locations', 'project_count')) {
            Schema::table('fsm_locations', function (Blueprint $table) {
                $table->integer('project_count')->default(0)->after('active');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('fsm_orders')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                if (Schema::hasColumn('fsm_orders', 'project_id')) $table->dropColumn('project_id');
                if (Schema::hasColumn('fsm_orders', 'task_id')) $table->dropColumn('task_id');
            });
        }
        if (Schema::hasTable('fsm_teams') && Schema::hasColumn('fsm_teams', 'project_id')) {
            Schema::table('fsm_teams', function (Blueprint $table) { $table->dropColumn('project_id'); });
        }
    }
};
