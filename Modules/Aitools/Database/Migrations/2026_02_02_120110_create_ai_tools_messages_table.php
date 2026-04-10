<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ai_tools_messages')) {
            return;
        }

        Schema::create('ai_tools_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('conversation_id')->index();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('role', 32)->default('user')->index();
            $table->longText('content');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('conversation_id')
                ->references('id')->on('ai_tools_conversations')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_tools_messages');
    }
};
