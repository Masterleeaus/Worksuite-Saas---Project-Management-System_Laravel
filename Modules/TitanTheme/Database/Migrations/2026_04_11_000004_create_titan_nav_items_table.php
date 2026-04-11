<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_nav_items')) {
            Schema::create('titan_nav_items', function (Blueprint $table) {
                $table->id();

                $table->unsignedInteger('company_id')->nullable()->index();
                $table->foreign('company_id')
                    ->references('id')->on('companies')
                    ->onDelete('cascade')->onUpdate('cascade');

                // Parent for hierarchy
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->foreign('parent_id')
                    ->references('id')->on('titan_nav_items')
                    ->onDelete('cascade')->onUpdate('cascade');

                $table->string('label');
                $table->string('url')->nullable();
                $table->string('route_name', 200)->nullable();
                $table->string('icon', 100)->nullable();

                // 'sidebar' | 'header'
                $table->string('panel', 20)->default('sidebar');

                // 'link' | 'separator' | 'heading'
                $table->string('item_type', 30)->default('link');

                $table->boolean('is_active')->default(true);
                $table->boolean('open_in_new_tab')->default(false);

                // Comma-separated role names that can see this item (null = all)
                $table->text('visible_to_roles')->nullable();

                // Optional: restrict to a specific module being enabled
                $table->string('required_module', 100)->nullable();

                $table->unsignedSmallInteger('sort_order')->default(0);

                $table->unsignedInteger('created_by')->nullable();
                $table->foreign('created_by')
                    ->references('id')->on('users')
                    ->onDelete('set null')->onUpdate('cascade');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_nav_items');
    }
};
