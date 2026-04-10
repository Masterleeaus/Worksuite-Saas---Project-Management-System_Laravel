<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ai_tools_settings')) {
            return;
        }

        Schema::table('ai_tools_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_tools_settings', 'provider_id')) {
                $table->unsignedBigInteger('provider_id')->nullable()->after('chatgpt_api_key')->index();
            }
            if (!Schema::hasColumn('ai_tools_settings', 'model_id')) {
                $table->unsignedBigInteger('model_id')->nullable()->after('provider_id')->index();
            }
            if (!Schema::hasColumn('ai_tools_settings', 'model_name')) {
                $table->string('model_name')->nullable()->after('model_id');
            }
            if (!Schema::hasColumn('ai_tools_settings', 'feature_flags')) {
                $table->json('feature_flags')->nullable()->after('model_name');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('ai_tools_settings')) {
            return;
        }

        Schema::table('ai_tools_settings', function (Blueprint $table) {
            if (Schema::hasColumn('ai_tools_settings', 'feature_flags')) {
                $table->dropColumn('feature_flags');
            }
            if (Schema::hasColumn('ai_tools_settings', 'model_name')) {
                $table->dropColumn('model_name');
            }
            if (Schema::hasColumn('ai_tools_settings', 'model_id')) {
                $table->dropColumn('model_id');
            }
            if (Schema::hasColumn('ai_tools_settings', 'provider_id')) {
                $table->dropColumn('provider_id');
            }
        });
    }
};
