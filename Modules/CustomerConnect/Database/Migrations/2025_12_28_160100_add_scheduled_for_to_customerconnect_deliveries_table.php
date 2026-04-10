<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customerconnect_deliveries', function (Blueprint $table) {
            if (!Schema::hasColumn('customerconnect_deliveries', 'scheduled_for')) {
                $table->timestamp('scheduled_for')->nullable()->index()->after('status');
            }
            if (!Schema::hasColumn('customerconnect_deliveries', 'last_attempt_at')) {
                $table->timestamp('last_attempt_at')->nullable()->after('attempts');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customerconnect_deliveries', function (Blueprint $table) {
            if (Schema::hasColumn('customerconnect_deliveries', 'scheduled_for')) {
                $table->dropColumn('scheduled_for');
            }
            if (Schema::hasColumn('customerconnect_deliveries', 'last_attempt_at')) {
                $table->dropColumn('last_attempt_at');
            }
        });
    }
};
