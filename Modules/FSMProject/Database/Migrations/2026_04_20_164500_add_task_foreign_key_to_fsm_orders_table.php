<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasTable('tasks') || ! Schema::hasColumn('fsm_orders', 'task_id')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE `fsm_orders` MODIFY `task_id` INT UNSIGNED NULL');
        }

        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->foreign('task_id')->references('id')->on('tasks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasColumn('fsm_orders', 'task_id')) {
            return;
        }

        Schema::table('fsm_orders', function (Blueprint $table) {
            try {
                $table->dropForeign(['task_id']);
            } catch (\Throwable $e) {
                // no-op
            }
        });
    }
};
