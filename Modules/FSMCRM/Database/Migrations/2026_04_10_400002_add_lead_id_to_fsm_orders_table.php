<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('lead_id')->nullable()->after('agreement_id')->index();
            $table->foreign('lead_id')->references('id')->on('fsm_leads')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->dropForeign(['lead_id']);
            $table->dropColumn('lead_id');
        });
    }
};
