<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_kb_documents')) {
            return;
        }

        Schema::create('ai_kb_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->unsignedBigInteger('source_id')->nullable()->index();
            $table->string('title');
            $table->string('doc_type')->default('text'); // text|pdf|html|json|csv|other
            $table->text('content')->nullable(); // optional raw text (small docs)
            $table->string('storage_path')->nullable(); // if uploaded file stored elsewhere
            $table->string('content_hash')->nullable()->index();

            $table->string('status')->default('stored'); // stored|chunked|embedded|error
            $table->text('last_error')->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['company_id','user_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_kb_documents');
    }
};
