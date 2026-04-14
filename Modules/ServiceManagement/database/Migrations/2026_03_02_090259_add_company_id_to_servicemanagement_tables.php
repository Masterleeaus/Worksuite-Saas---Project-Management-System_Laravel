<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ai_kb_documents') && !Schema::hasColumn('ai_kb_documents', 'company_id')) {
            Schema::table('ai_kb_documents', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('faqs') && !Schema::hasColumn('faqs', 'company_id')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('favorite_services') && !Schema::hasColumn('favorite_services', 'company_id')) {
            Schema::table('favorite_services', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('recent_searches') && !Schema::hasColumn('recent_searches', 'company_id')) {
            Schema::table('recent_searches', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('recent_views') && !Schema::hasColumn('recent_views', 'company_id')) {
            Schema::table('recent_views', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('service_requests') && !Schema::hasColumn('service_requests', 'company_id')) {
            Schema::table('service_requests', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('service_tag') && !Schema::hasColumn('service_tag', 'company_id')) {
            Schema::table('service_tag', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('services') && !Schema::hasColumn('services', 'company_id')) {
            Schema::table('services', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('tags') && !Schema::hasColumn('tags', 'company_id')) {
            Schema::table('tags', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('variations') && !Schema::hasColumn('variations', 'company_id')) {
            Schema::table('variations', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('visited_services') && !Schema::hasColumn('visited_services', 'company_id')) {
            Schema::table('visited_services', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
