<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chatbot_embeddings')) {
            return;
        }

        Schema::create('chatbot_embeddings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatbot_id');
            $table->string('source_type'); // article, faq, canned
            $table->unsignedBigInteger('source_id');
            $table->string('embedding_model')->nullable();
            $table->longText('vector_data')->nullable(); // JSON array of floats
            $table->string('checksum')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index('chatbot_id');
            $table->index(['source_type', 'source_id']);

            $table->foreign('chatbot_id')->references('id')->on('chatbots')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_embeddings');
    }
};
