<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('complaint')) {
            return;
        }

        Schema::table('complaint', function (Blueprint $table) {
            if (!Schema::hasColumn('complaint', 'qc_followup_required')) {
                $table->boolean('qc_followup_required')->default(false)->after('qc_record_id');
            }

            if (!Schema::hasColumn('complaint', 'qc_followup_due_at')) {
                $table->timestamp('qc_followup_due_at')->nullable()->after('qc_followup_required');
            }

            if (!Schema::hasColumn('complaint', 'qc_resolution_status')) {
                $table->string('qc_resolution_status', 50)->nullable()->after('qc_followup_due_at');
                $table->index('qc_resolution_status');
            }
        });
    }

    public function down(): void
    {
        // Non-destructive rollback for production safety.
    }
};
