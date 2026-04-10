<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('schedules')) {
            return;
        }

        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('schedules', 'starts_at')) {
                $table->dateTime('starts_at')->nullable()->after('date')->index();
            }
            if (!Schema::hasColumn('schedules', 'ends_at')) {
                $table->dateTime('ends_at')->nullable()->after('starts_at')->index();
            }
            if (!Schema::hasColumn('schedules', 'timezone')) {
                $table->string('timezone', 64)->nullable()->after('ends_at');
            }
            if (!Schema::hasColumn('schedules', 'location')) {
                $table->string('location')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('schedules', 'notes')) {
                $table->longText('notes')->nullable()->after('questions');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('schedules')) {
            return;
        }

        Schema::table('schedules', function (Blueprint $table) {
            // Best-effort reversals.
            foreach (['company_id','starts_at','ends_at','timezone','location','notes'] as $col) {
                try { if (Schema::hasColumn('schedules', $col)) { $table->dropColumn($col); } } catch (\Throwable $e) {}
            }
        });
    }
};
