<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            }
            if (!Schema::hasColumn('faqs', 'visibility')) {
                $table->string('visibility', 32)->default('team')->index();
            }
            if (!Schema::hasColumn('faqs', 'source_type')) {
                $table->string('source_type', 32)->default('user')->index();
            }
            if (!Schema::hasColumn('faqs', 'tags')) {
                $table->json('tags')->nullable();
            }
            if (!Schema::hasColumn('faqs', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
            if (!Schema::hasColumn('faqs', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            foreach (['updated_by','created_by','tags','source_type','visibility','company_id'] as $col) {
                if (Schema::hasColumn('faqs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
