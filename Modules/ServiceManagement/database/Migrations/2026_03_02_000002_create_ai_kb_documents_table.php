<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_kb_documents')) {
            return;
        }

        Schema::create('ai_kb_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Tenant scoping (nullable allows global/shared KB docs)
            $table->unsignedBigInteger('company_id')->nullable()->index();

            // Source pointer (e.g. faqs:id)
            $table->string('source_table', 64)->index();
            $table->uuid('source_id')->index();

            $table->string('title', 191)->nullable();
            $table->longText('content');

            // Change detection
            $table->string('content_hash', 64)->nullable()->index();

            // Embedding metadata (vector storage can be implemented elsewhere; keep fields ready)
            $table->string('embedding_provider', 64)->nullable();
            $table->string('embedding_model', 128)->nullable();
            $table->longText('embedding')->nullable(); // JSON / base64 / vendor-specific payload

            $table->timestamp('last_indexed_at')->nullable();
            $table->string('status', 32)->default('pending')->index(); // pending | indexed | failed

            $table->timestamps();

            $table->unique(['source_table', 'source_id', 'company_id'], 'ai_kb_source_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_kb_documents');
    }
};
