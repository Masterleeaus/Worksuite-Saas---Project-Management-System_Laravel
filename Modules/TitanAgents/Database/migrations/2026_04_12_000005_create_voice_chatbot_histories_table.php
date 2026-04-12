<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\TitanAgents\Enums\Voice\RoleEnum;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('voice_chatbot_histories')) {
            return;
        }

        Schema::create('voice_chatbot_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->foreign('conversation_id')->references('id')->on('voice_chatbot_conversations')->cascadeOnDelete();
            $table->enum('role', RoleEnum::toArray());
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_chatbot_histories');
    }
};
