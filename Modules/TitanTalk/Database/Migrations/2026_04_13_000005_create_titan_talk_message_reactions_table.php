<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_talk_message_reactions')) {
            Schema::create('titan_talk_message_reactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('message_id')->index();
                $table->unsignedInteger('user_id')->index();
                $table->string('emoji', 64);
                $table->timestamps();

                $table->unique(['message_id', 'user_id', 'emoji']);
                $table->foreign('message_id')->references('id')->on('titan_talk_messages')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_talk_message_reactions');
    }
};
