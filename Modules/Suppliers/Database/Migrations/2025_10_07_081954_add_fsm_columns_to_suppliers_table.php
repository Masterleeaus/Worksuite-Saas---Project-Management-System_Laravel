<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->unsignedTinyInteger('fsm_rating')->nullable()->after('email');
            $table->unsignedSmallInteger('fsm_lead_time_days')->nullable()->after('fsm_rating');
            $table->string('fsm_payment_terms')->nullable()->after('fsm_lead_time_days');
        });
    }
    public function down(): void {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['fsm_rating','fsm_lead_time_days','fsm_payment_terms']);
        });
    }
};
