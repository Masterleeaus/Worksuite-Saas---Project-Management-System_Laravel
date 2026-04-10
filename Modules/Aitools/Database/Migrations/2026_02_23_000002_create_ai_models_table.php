<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_models')) {
            return;
        }

        Schema::create('ai_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index(); // null = global
            $table->unsignedBigInteger('provider_id')->nullable()->index();
            $table->string('name');
            $table->string('model_type')->default('chat'); // chat|embedding|image
            $table->integer('max_output_tokens')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->json('pricing')->nullable(); // optional metadata
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'provider_id', 'model_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_models');
    }
};
