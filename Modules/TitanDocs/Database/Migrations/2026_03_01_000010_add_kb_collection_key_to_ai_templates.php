<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ai_templates') && !Schema::hasColumn('ai_templates', 'kb_collection_key')) {
            Schema::table('ai_templates', function (Blueprint $t) {
                $t->string('kb_collection_key', 128)
                    ->default('kb_general_cleaning')
                    ->after('template_code');
                $t->index('kb_collection_key');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ai_templates') && Schema::hasColumn('ai_templates', 'kb_collection_key')) {
            Schema::table('ai_templates', function (Blueprint $t) {
                $t->dropIndex(['kb_collection_key']);
                $t->dropColumn('kb_collection_key');
            });
        }
    }
};
