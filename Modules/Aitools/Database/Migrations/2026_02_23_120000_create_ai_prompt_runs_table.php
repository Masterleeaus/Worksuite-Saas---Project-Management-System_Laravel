<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_prompt_runs')) {
            return;
        }

        Schema::create('ai_prompt_runs', function (Blueprint $table) {
            $table->id();

            // Worksuite tenancy
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();

            // Prompt identity (denormalized so runs survive prompt edits)
            $table->string('namespace', 80)->index();
            $table->string('slug', 120)->index();
            $table->unsignedInteger('version')->default(1);
            $table->string('locale', 12)->default('en');

            $table->string('operation', 40)->default('prompt_run');
            $table->string('status', 20)->default('ok'); // ok|error

            $table->longText('input_json')->nullable();
            $table->longText('output_text')->nullable();
            $table->text('error_message')->nullable();

            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('completion_tokens')->default(0);
            $table->unsignedInteger('total_tokens')->default(0);

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['company_id', 'namespace', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_prompt_runs');
    }
};
