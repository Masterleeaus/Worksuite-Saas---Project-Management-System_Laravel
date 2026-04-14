<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chatbot_knowledge_base_articles')) {
            return;
        }

        Schema::create('chatbot_knowledge_base_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatbot_id');
            $table->string('title');
            $table->text('content');
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->string('status')->default('active');
            $table->string('embedding_status')->default('pending'); // pending, processing, done, failed
            $table->integer('views')->default(0);
            $table->integer('helpful_count')->default(0);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('chatbot_id');
            $table->index('status');
            $table->index('embedding_status');

            $table->foreign('chatbot_id')->references('id')->on('chatbots')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_knowledge_base_articles');
    }
};
