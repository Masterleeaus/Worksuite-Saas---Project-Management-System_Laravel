<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasTable('projects') || ! Schema::hasColumn('fsm_orders', 'project_id')) {
            return;
        }

        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasColumn('fsm_orders', 'project_id')) {
            return;
        }

        Schema::table('fsm_orders', function (Blueprint $table) {
            try {
                $table->dropForeign(['project_id']);
            } catch (\Throwable $e) {
                // no-op
            }
        });
    }
};
