<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_talk_room_pins')) {
            Schema::create('titan_talk_room_pins', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('room_id')->index();
                $table->unsignedBigInteger('message_id')->index();
                $table->unsignedInteger('pinned_by')->nullable();
                $table->timestamps();

                $table->unique(['room_id', 'message_id']);
                $table->foreign('room_id')->references('id')->on('titan_talk_rooms')->onDelete('cascade');
                $table->foreign('message_id')->references('id')->on('titan_talk_messages')->onDelete('cascade');
                $table->foreign('pinned_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_talk_room_pins');
    }
};
