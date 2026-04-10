<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customerconnect_campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('customerconnect_campaigns', 'stop_on_reply')) {
                $table->boolean('stop_on_reply')->default(true)->after('status')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('customerconnect_campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('customerconnect_campaigns', 'stop_on_reply')) {
                $table->dropColumn('stop_on_reply');
            }
        });
    }
};
