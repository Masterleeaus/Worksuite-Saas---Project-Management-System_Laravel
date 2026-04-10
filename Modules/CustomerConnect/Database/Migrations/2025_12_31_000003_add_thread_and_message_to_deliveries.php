<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customerconnect_deliveries', function (Blueprint $table) {
            if (!Schema::hasColumn('customerconnect_deliveries', 'thread_id')) {
                $table->unsignedBigInteger('thread_id')->nullable()->index()->after('contact_id');
            }
            if (!Schema::hasColumn('customerconnect_deliveries', 'message_id')) {
                $table->unsignedBigInteger('message_id')->nullable()->index()->after('thread_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customerconnect_deliveries', function (Blueprint $table) {
            if (Schema::hasColumn('customerconnect_deliveries', 'message_id')) {
                $table->dropColumn('message_id');
            }
            if (Schema::hasColumn('customerconnect_deliveries', 'thread_id')) {
                $table->dropColumn('thread_id');
            }
        });
    }
};
