<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customerconnect_contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('customerconnect_contacts', 'telegram_user_id')) {
                $table->string('telegram_user_id')->nullable()->index()->after('telegram_chat_id');
            }
        });

        Schema::table('customerconnect_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('customerconnect_messages', 'sender_contact_id')) {
                $table->unsignedBigInteger('sender_contact_id')->nullable()->index()->after('direction');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customerconnect_messages', function (Blueprint $table) {
            if (Schema::hasColumn('customerconnect_messages', 'sender_contact_id')) {
                $table->dropColumn('sender_contact_id');
            }
        });

        Schema::table('customerconnect_contacts', function (Blueprint $table) {
            if (Schema::hasColumn('customerconnect_contacts', 'telegram_user_id')) {
                $table->dropColumn('telegram_user_id');
            }
        });
    }
};
