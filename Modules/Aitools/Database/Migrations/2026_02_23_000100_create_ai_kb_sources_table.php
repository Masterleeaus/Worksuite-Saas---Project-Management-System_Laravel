<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_kb_sources')) {
            return;
        }

        Schema::create('ai_kb_sources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index(); // null = global
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('source_type')->default('upload'); // upload|url|api|crawler
            $table->text('source_uri')->nullable(); // url or identifier
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable(); // headers, auth hints, crawl rules, etc
            $table->timestamps();

            $table->index(['company_id', 'user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_kb_sources');
    }
};
