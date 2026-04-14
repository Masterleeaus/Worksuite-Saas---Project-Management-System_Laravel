<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chatbot_canned_responses')) {
            return;
        }

        Schema::create('chatbot_canned_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatbot_id');
            $table->string('shortcut')->nullable();
            $table->string('title');
            $table->text('content');
            $table->string('category')->nullable();
            $table->integer('use_count')->default(0);
            $table->string('status')->default('active');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('chatbot_id');
            $table->index('status');

            $table->foreign('chatbot_id')->references('id')->on('chatbots')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_canned_responses');
    }
};
