<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chatbots')) {
            return;
        }

        Schema::create('chatbots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('ai_provider')->default('openai');
            $table->string('ai_model')->nullable();
            $table->text('system_prompt')->nullable();
            $table->string('welcome_message')->nullable();
            $table->string('fallback_message')->nullable();
            $table->decimal('temperature', 3, 2)->default(0.70);
            $table->integer('max_tokens')->default(2000);
            $table->string('status')->default('active');
            $table->integer('plan_limit')->default(1);
            $table->json('settings')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbots');
    }
};
