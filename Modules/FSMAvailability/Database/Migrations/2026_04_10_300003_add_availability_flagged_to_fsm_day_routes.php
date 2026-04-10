<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('fsm_day_routes') && !Schema::hasColumn('fsm_day_routes', 'availability_flagged')) {
            Schema::table('fsm_day_routes', function (Blueprint $table) {
                $table->boolean('availability_flagged')->default(false)->after('max_allow_time');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('fsm_day_routes') && Schema::hasColumn('fsm_day_routes', 'availability_flagged')) {
            Schema::table('fsm_day_routes', function (Blueprint $table) {
                $table->dropColumn('availability_flagged');
            });
        }
    }
};
