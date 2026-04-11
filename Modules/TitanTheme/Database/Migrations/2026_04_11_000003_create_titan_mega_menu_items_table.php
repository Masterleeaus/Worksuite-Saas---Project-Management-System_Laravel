<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_mega_menu_items')) {
            Schema::create('titan_mega_menu_items', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('mega_menu_id');
                $table->foreign('mega_menu_id')
                    ->references('id')->on('titan_mega_menus')
                    ->onDelete('cascade')->onUpdate('cascade');

                // Parent item (for nested groups)
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->foreign('parent_id')
                    ->references('id')->on('titan_mega_menu_items')
                    ->onDelete('cascade')->onUpdate('cascade');

                $table->string('label');
                $table->string('url')->nullable();
                $table->string('route_name', 200)->nullable();
                $table->string('icon', 100)->nullable();
                $table->string('description')->nullable();
                $table->string('thumbnail_path')->nullable();

                // 'link' | 'group' | 'featured'
                $table->string('item_type', 30)->default('link');

                $table->boolean('open_in_new_tab')->default(false);
                $table->boolean('is_active')->default(true);
                $table->boolean('is_featured')->default(false);

                // Optional: restrict to a specific module being enabled
                $table->string('required_module', 100)->nullable();

                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->unsignedSmallInteger('column_span')->default(1);

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_mega_menu_items');
    }
};
