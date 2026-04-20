<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('fsm_recurrings', 'agreement_id')) {
            Schema::table('fsm_recurrings', function (Blueprint $table) {
                $table->unsignedBigInteger('agreement_id')->nullable()->after('id');
                $table->foreign('agreement_id')
                    ->references('id')->on('fsm_service_agreements')
                    ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('fsm_recurrings', function (Blueprint $table) {
            if (Schema::hasColumn('fsm_recurrings', 'agreement_id')) {
                $table->dropForeign(['agreement_id']);
                $table->dropColumn('agreement_id');
            }
        });
    }
};
