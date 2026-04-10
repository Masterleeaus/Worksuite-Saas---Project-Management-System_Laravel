<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_kb_chunks')) {
            return;
        }

        Schema::create('ai_kb_chunks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->unsignedBigInteger('document_id')->index();
            $table->unsignedInteger('chunk_index')->default(0);
            $table->longText('chunk_text');
            $table->json('embedding')->nullable(); // keep JSON for now (portable)
            $table->string('embedding_model')->nullable();
            $table->timestamps();

            $table->unique(['document_id','chunk_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_kb_chunks');
    }
};
