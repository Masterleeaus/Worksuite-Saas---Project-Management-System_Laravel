<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customerconnect_deliveries', function (Blueprint $table) {
            if (!Schema::hasColumn('customerconnect_deliveries', 'provider')) {
                $table->string('provider')->nullable()->after('status');
            }
            if (!Schema::hasColumn('customerconnect_deliveries', 'provider_message_id')) {
                $table->string('provider_message_id')->nullable()->after('provider');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customerconnect_deliveries', function (Blueprint $table) {
            if (Schema::hasColumn('customerconnect_deliveries', 'provider_message_id')) {
                $table->dropColumn('provider_message_id');
            }
            if (Schema::hasColumn('customerconnect_deliveries', 'provider')) {
                $table->dropColumn('provider');
            }
        });
    }
};
