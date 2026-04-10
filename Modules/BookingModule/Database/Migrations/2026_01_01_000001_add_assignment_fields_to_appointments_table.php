<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                if (!Schema::hasColumn('appointments', 'assigned_to')) {
                    $table->unsignedBigInteger('assigned_to')->nullable()->after('created_by');
                }
                if (!Schema::hasColumn('appointments', 'assigned_by')) {
                    $table->unsignedBigInteger('assigned_by')->nullable()->after('assigned_to');
                }
                if (!Schema::hasColumn('appointments', 'assigned_at')) {
                    $table->dateTime('assigned_at')->nullable()->after('assigned_by');
                }
                if (!Schema::hasColumn('appointments', 'assignment_status')) {
                    $table->string('assignment_status', 50)->default('unassigned')->after('assigned_at');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                if (Schema::hasColumn('appointments', 'assignment_status')) {
                    $table->dropColumn('assignment_status');
                }
                if (Schema::hasColumn('appointments', 'assigned_at')) {
                    $table->dropColumn('assigned_at');
                }
                if (Schema::hasColumn('appointments', 'assigned_by')) {
                    $table->dropColumn('assigned_by');
                }
                if (Schema::hasColumn('appointments', 'assigned_to')) {
                    $table->dropColumn('assigned_to');
                }
            });
        }
    }
};
