<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_talk_message_saves')) {
            Schema::create('titan_talk_message_saves', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->index();
                $table->unsignedBigInteger('message_id')->index();
                $table->timestamps();

                $table->unique(['user_id', 'message_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('message_id')->references('id')->on('titan_talk_messages')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_talk_message_saves');
    }
};
