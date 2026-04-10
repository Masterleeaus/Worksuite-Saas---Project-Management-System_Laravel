<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ai_prompt_histories') && !Schema::hasColumn('ai_prompt_histories', 'company_id')) {
            Schema::table('ai_prompt_histories', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('ai_prompt_responses') && !Schema::hasColumn('ai_prompt_responses', 'company_id')) {
            Schema::table('ai_prompt_responses', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('ai_template_categories') && !Schema::hasColumn('ai_template_categories', 'company_id')) {
            Schema::table('ai_template_categories', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('ai_template_languages') && !Schema::hasColumn('ai_template_languages', 'company_id')) {
            Schema::table('ai_template_languages', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('ai_template_prompts') && !Schema::hasColumn('ai_template_prompts', 'company_id')) {
            Schema::table('ai_template_prompts', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('ai_templates') && !Schema::hasColumn('ai_templates', 'company_id')) {
            Schema::table('ai_templates', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
