<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_providers')) {
            return;
        }

        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index(); // null = global
            $table->string('name');
            $table->string('driver')->default('openai'); // openai, anthropic, etc
            $table->string('base_url')->nullable();
            $table->text('api_key')->nullable(); // encrypted via model cast
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_providers');
    }
};
