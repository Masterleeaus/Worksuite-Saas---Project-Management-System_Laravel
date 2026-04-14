<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chatbot_page_visits')) {
            return;
        }

        Schema::create('chatbot_page_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatbot_id');
            $table->string('session_id')->nullable();
            $table->string('page_url')->nullable();
            $table->string('referrer')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('visited_at')->nullable();
            $table->timestamps();

            $table->index('chatbot_id');
            $table->index('visited_at');

            $table->foreign('chatbot_id')->references('id')->on('chatbots')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_page_visits');
    }
};
