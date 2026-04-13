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
        
        Schema::table('fsm_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('fsm_orders', 'vehicle_id')) {
                $table->unsignedBigInteger('vehicle_id')->nullable()->after('person_id')->index();
            }
            $table->foreign('vehicle_id')->references('id')->on('fsm_vehicles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }
        
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn('vehicle_id');
        });
    }
};
