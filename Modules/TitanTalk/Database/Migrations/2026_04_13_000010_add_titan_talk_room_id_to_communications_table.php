<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a dedicated titan_talk_room_id column to the communications table so
 * TitanTalk audit entries no longer need to overload the legacy booking_id field.
 *
 * This migration is safe to run even when the Communication module is not installed:
 * it only runs if the 'communications' table actually exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('communications')) {
            return;
        }

        if (!Schema::hasColumn('communications', 'titan_talk_room_id')) {
            Schema::table('communications', function (Blueprint $table) {
                $table->unsignedBigInteger('titan_talk_room_id')
                    ->nullable()
                    ->after('booking_id')
                    ->index()
                    ->comment('FK to titan_talk_rooms.id; populated by TitanTalkService::logToCommunication()');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('communications')) {
            return;
        }

        if (Schema::hasColumn('communications', 'titan_talk_room_id')) {
            Schema::table('communications', function (Blueprint $table) {
                $table->dropIndex(['titan_talk_room_id']);
                $table->dropColumn('titan_talk_room_id');
            });
        }
    }
};
