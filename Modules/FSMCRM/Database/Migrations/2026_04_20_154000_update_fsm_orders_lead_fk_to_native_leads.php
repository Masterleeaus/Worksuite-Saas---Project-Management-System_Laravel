<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }

        if (! Schema::hasColumn('fsm_orders', 'lead_id')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->unsignedInteger('lead_id')->nullable()->after('agreement_id')->index();
            });
        }

        $this->dropLeadForeignKey();

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->unsignedInteger('lead_id')->nullable()->change();
            });
        }

        if (Schema::hasTable('leads')) {
            try {
                Schema::table('fsm_orders', function (Blueprint $table) {
                    $table->foreign('lead_id')->references('id')->on('leads')->nullOnDelete();
                });
            } catch (\Throwable) {
                // FK already exists or is not supported by current connection.
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }

        $this->dropLeadForeignKey();
    }

    private function dropLeadForeignKey(): void
    {
        if (! Schema::hasColumn('fsm_orders', 'lead_id')) {
            return;
        }

        try {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->dropForeign(['lead_id']);
            });
        } catch (\Throwable) {
            // No FK exists yet.
        }
    }
};
