<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_kb_collection_docs')) {
            return;
        }

        Schema::create('ai_kb_collection_docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id')->index();
            $table->unsignedBigInteger('document_id')->index();
            $table->timestamps();

            $table->unique(['collection_id','document_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_kb_collection_docs');
    }
};
