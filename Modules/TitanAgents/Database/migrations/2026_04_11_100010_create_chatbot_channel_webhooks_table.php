<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chatbot_channel_webhooks')) {
            return;
        }

        Schema::create('chatbot_channel_webhooks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatbot_id');
            $table->string('channel_type');
            $table->string('webhook_secret')->nullable();
            $table->string('webhook_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_called_at')->nullable();
            $table->integer('call_count')->default(0);
            $table->timestamps();

            $table->index('chatbot_id');
            $table->index('channel_type');

            $table->foreign('chatbot_id')->references('id')->on('chatbots')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_channel_webhooks');
    }
};
