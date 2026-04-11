<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('projectroadmap_items')) {
            Schema::table('projectroadmap_items', function (Blueprint $table) {
                if (!Schema::hasColumn('projectroadmap_items', 'status')) {
                    $table->enum('status', ['planned', 'in_progress', 'in_review', 'launched', 'cancelled'])
                          ->default('planned')
                          ->after('description');
                }
                if (!Schema::hasColumn('projectroadmap_items', 'category')) {
                    $table->string('category')->nullable()->after('status');
                }
                if (!Schema::hasColumn('projectroadmap_items', 'votes')) {
                    $table->unsignedInteger('votes')->default(0)->after('category');
                }
                if (!Schema::hasColumn('projectroadmap_items', 'is_public')) {
                    $table->boolean('is_public')->default(true)->after('votes');
                }
                if (!Schema::hasColumn('projectroadmap_items', 'target_release')) {
                    $table->string('target_release')->nullable()->after('is_public');
                }
                if (!Schema::hasColumn('projectroadmap_items', 'release_notes')) {
                    $table->text('release_notes')->nullable()->after('target_release');
                }
                if (!Schema::hasColumn('projectroadmap_items', 'added_by')) {
                    $table->unsignedBigInteger('added_by')->nullable()->after('release_notes');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('projectroadmap_items')) {
            Schema::table('projectroadmap_items', function (Blueprint $table) {
                $table->dropColumnIfExists('status');
                $table->dropColumnIfExists('category');
                $table->dropColumnIfExists('votes');
                $table->dropColumnIfExists('is_public');
                $table->dropColumnIfExists('target_release');
                $table->dropColumnIfExists('release_notes');
                $table->dropColumnIfExists('added_by');
            });
        }
    }
};
