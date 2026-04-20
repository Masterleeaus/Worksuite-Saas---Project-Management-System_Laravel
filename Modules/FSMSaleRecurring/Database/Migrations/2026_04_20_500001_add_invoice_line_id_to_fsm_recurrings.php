<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fsm_recurrings', function (Blueprint $table) {
            if (!Schema::hasColumn('fsm_recurrings', 'invoice_line_id')) {
                $table->unsignedBigInteger('invoice_line_id')
                    ->nullable()
                    ->after('id');

                $table->foreign('invoice_line_id')
                    ->references('id')
                    ->on('invoice_items')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fsm_recurrings', function (Blueprint $table) {
            if (Schema::hasColumn('fsm_recurrings', 'invoice_line_id')) {
                $table->dropForeign(['invoice_line_id']);
                $table->dropColumn('invoice_line_id');
            }
        });
    }
};
