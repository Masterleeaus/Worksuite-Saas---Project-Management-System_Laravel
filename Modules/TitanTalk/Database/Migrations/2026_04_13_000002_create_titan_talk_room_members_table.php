<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_talk_room_members')) {
            Schema::create('titan_talk_room_members', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('room_id')->index();
                $table->unsignedInteger('user_id')->index();
                $table->enum('role', ['owner', 'admin', 'member'])->default('member');
                $table->boolean('is_muted')->default(false);
                $table->timestamp('last_read_at')->nullable();
                $table->timestamps();

                $table->unique(['room_id', 'user_id']);
                $table->foreign('room_id')->references('id')->on('titan_talk_rooms')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_talk_room_members');
    }
};
