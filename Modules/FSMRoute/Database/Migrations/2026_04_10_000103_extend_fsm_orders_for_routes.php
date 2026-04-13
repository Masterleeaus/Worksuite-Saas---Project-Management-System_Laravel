<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }
        
        Schema::table('fsm_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('fsm_orders', 'dayroute_id')) {
                $table->unsignedBigInteger('dayroute_id')->nullable()->after('template_id')->index();
            }
            if (! Schema::hasColumn('fsm_orders', 'route_sequence')) {
                $table->unsignedInteger('route_sequence')->default(0)->after('dayroute_id')->nullable();
            }

            $table->foreign('dayroute_id')->references('id')->on('fsm_day_routes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('fsm_orders')) {
            return;
        }
        
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->dropForeign(['dayroute_id']);
            $table->dropColumn(['dayroute_id', 'route_sequence']);
        });
    }
};
