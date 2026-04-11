<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chatbot_conversations')) {
            return;
        }

        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatbot_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('channel_type')->nullable();
            $table->string('session_id')->unique()->nullable();
            $table->string('status')->default('open'); // open, resolved, escalated
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('message_count')->default(0);
            $table->timestamps();

            $table->index('chatbot_id');
            $table->index('status');
            $table->index('session_id');

            $table->foreign('chatbot_id')->references('id')->on('chatbots')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_conversations');
    }
};
