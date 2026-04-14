<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('voice_chatbots')) {
            return;
        }

        Schema::create('voice_chatbots', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('agent_id');
            $table->string('title');
            $table->string('bubble_message');
            $table->string('welcome_message');
            $table->text('instructions');
            $table->string('language')->nullable();
            $table->string('ai_model')->nullable();
            $table->string('avatar')->nullable();
            $table->string('voice_id');
            $table->string('position')->default('right');
            $table->boolean('active')->default(true);
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_chatbots');
    }
};
