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
            if (!Schema::hasColumn('schedules', 'email_verification_token')) {
                $table->string('email_verification_token', 80)->nullable()->index();
            }
            if (!Schema::hasColumn('schedules', 'email_verification_sent_at')) {
                $table->timestamp('email_verification_sent_at')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('schedules')) {
            return;
        }

        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'email_verification_token')) {
                $table->dropColumn('email_verification_token');
            }
            if (Schema::hasColumn('schedules', 'email_verification_sent_at')) {
                $table->dropColumn('email_verification_sent_at');
            }
            if (Schema::hasColumn('schedules', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }
};
