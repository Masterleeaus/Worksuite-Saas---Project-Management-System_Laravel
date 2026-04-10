<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_tools_registry')) {
            return;
        }

        Schema::create('ai_tools_registry', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index(); // null = global
            $table->string('tool_name')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('input_schema')->nullable(); // JSON schema-like
            $table->string('risk_level')->default('low'); // low|medium|high
            $table->boolean('is_enabled')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_tools_registry');
    }
};
