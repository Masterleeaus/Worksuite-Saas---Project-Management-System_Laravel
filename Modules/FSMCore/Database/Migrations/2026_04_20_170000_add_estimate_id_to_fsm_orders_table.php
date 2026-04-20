<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasTable('estimates') || Schema::hasColumn('fsm_orders', 'estimate_id')) {
            return;
        }

        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->unsignedInteger('estimate_id')->nullable()->index();
        });

        try {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->foreign('estimate_id')->references('id')->on('estimates')->nullOnDelete();
            });
        } catch (\Throwable) {
            // FK already exists or unsupported by current connection.
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasColumn('fsm_orders', 'estimate_id')) {
            return;
        }

        try {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->dropForeign(['estimate_id']);
            });
        } catch (\Throwable) {
            // No FK exists.
        }

        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->dropColumn('estimate_id');
        });
    }
};
