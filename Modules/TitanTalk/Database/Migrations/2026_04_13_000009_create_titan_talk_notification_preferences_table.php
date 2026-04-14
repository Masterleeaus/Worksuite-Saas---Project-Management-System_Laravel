<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_talk_notification_preferences')) {
            Schema::create('titan_talk_notification_preferences', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->index();
                $table->unsignedBigInteger('room_id')->nullable()->index()->comment('NULL = global preference');
                $table->boolean('notify_all_messages')->default(true);
                $table->boolean('notify_mentions')->default(true);
                $table->boolean('notify_email')->default(false);
                $table->boolean('notify_sms')->default(false);
                $table->timestamps();

                $table->unique(['user_id', 'room_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('room_id')->references('id')->on('titan_talk_rooms')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_talk_notification_preferences');
    }
};
