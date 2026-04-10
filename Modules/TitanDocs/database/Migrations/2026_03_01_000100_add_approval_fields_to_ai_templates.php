<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ai_templates')) {
            Schema::table('ai_templates', function (Blueprint $table) {
                if (!Schema::hasColumn('ai_templates', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('updated_at');
                }
                if (!Schema::hasColumn('ai_templates', 'approved_by')) {
                    $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ai_templates')) {
            Schema::table('ai_templates', function (Blueprint $table) {
                if (Schema::hasColumn('ai_templates', 'approved_by')) {
                    $table->dropColumn('approved_by');
                }
                if (Schema::hasColumn('ai_templates', 'approved_at')) {
                    $table->dropColumn('approved_at');
                }
            });
        }
    }
};
