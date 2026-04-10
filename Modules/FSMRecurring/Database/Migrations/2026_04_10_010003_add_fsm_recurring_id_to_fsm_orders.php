<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add fsm_recurring_id to fsm_orders (FK back to the recurring schedule)
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('fsm_recurring_id')->nullable()->after('agreement_id')->index();
            $table->foreign('fsm_recurring_id')->references('id')->on('fsm_recurrings')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fsm_orders', function (Blueprint $table) {
            $table->dropForeign(['fsm_recurring_id']);
            $table->dropColumn('fsm_recurring_id');
        });
    }
};
