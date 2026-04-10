<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('project_roadmap_settings') && !Schema::hasColumn('project_roadmap_settings', 'company_id')) {
            Schema::table('project_roadmap_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
        if (Schema::hasTable('projectroadmap_items') && !Schema::hasColumn('projectroadmap_items', 'company_id')) {
            Schema::table('projectroadmap_items', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // intentionally non-destructive
    }
};
