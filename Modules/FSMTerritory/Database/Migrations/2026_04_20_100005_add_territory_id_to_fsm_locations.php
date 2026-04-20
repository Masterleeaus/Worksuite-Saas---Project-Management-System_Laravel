<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fsm_locations') && ! Schema::hasColumn('fsm_locations', 'territory_id')) {
            Schema::table('fsm_locations', function (Blueprint $table) {
                $table->unsignedBigInteger('territory_id')->nullable()->after('id');
                $table->foreign('territory_id')->references('id')->on('fsm_territories')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('fsm_locations') && Schema::hasColumn('fsm_locations', 'territory_id')) {
            Schema::table('fsm_locations', function (Blueprint $table) {
                $table->dropForeign(['territory_id']);
                $table->dropColumn('territory_id');
            });
        }
    }
};
