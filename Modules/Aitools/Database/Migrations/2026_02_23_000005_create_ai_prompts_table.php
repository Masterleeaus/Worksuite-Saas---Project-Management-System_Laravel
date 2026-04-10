<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_prompts')) {
            return;
        }

        Schema::create('ai_prompts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index(); // null = global
            $table->string('namespace')->default('aitools');
            $table->string('slug');
            $table->integer('version')->default(1);
            $table->string('locale')->default('en');
            $table->string('status')->default('active'); // active|draft|archived
            $table->string('title')->nullable();
            $table->longText('prompt_body');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['company_id','namespace','slug','version','locale'], 'ai_prompts_unique');
            $table->index(['company_id','namespace','slug','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_prompts');
    }
};
