<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('voice_chatbot_conversations')) {
            return;
        }

        Schema::create('voice_chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->uuid('chatbot_uuid');
            $table->foreign('chatbot_uuid')->references('uuid')->on('voice_chatbots')->cascadeOnDelete();
            $table->string('conversation_id');
            $table->string('status')->default('processing');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_chatbot_conversations');
    }
};
