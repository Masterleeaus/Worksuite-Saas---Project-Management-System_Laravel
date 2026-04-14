<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\CategoryManagement\Entities\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
        });
    }

        Category::whereNull('slug')
            ->orWhere('slug', '')
            ->chunkById(100, function ($categories) {
                foreach ($categories as $category) {
                    $category->slug = Category::generateUniqueSlug($category->name, $category->id);
                    $category->save();
                }
            });

        Schema::table('categories', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('categories')) {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            if (Schema::hasColumn('categories', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
    }
};
