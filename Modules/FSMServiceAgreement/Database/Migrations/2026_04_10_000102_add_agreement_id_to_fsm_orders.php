<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Link FSM orders back to the service agreement that generated them
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }
        
        Schema::table('fsm_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('fsm_orders', 'agreement_id')) {
                $table->unsignedBigInteger('agreement_id')->nullable()->after('template_id')->index();
            }
            $table->foreign('agreement_id')
                ->references('id')->on('fsm_service_agreements')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }
        
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->dropForeign(['agreement_id']);
            $table->dropColumn('agreement_id');
        });
    }
};
