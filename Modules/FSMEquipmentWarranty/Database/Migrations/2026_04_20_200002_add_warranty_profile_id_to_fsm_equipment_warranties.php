<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('fsm_equipment_warranties', 'warranty_profile_id')) {
            Schema::table('fsm_equipment_warranties', function (Blueprint $table) {
                $table->unsignedBigInteger('warranty_profile_id')->nullable()->after('equipment_id');
                $table->foreign('warranty_profile_id')
                    ->references('id')->on('fsm_warranty_profiles')
                    ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('fsm_equipment_warranties', function (Blueprint $table) {
            if (Schema::hasColumn('fsm_equipment_warranties', 'warranty_profile_id')) {
                $table->dropForeign(['warranty_profile_id']);
                $table->dropColumn('warranty_profile_id');
            }
        });
    }
};
