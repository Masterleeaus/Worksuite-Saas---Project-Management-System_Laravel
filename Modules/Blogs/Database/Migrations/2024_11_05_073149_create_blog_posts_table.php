<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('blog_posts')) {
            return;
        }
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable()->index();
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('slug');
            $table->unsignedBigInteger('category');
            $table->text('description');
            $table->boolean('popular')->default(true);
            $table->boolean('status')->default(true);
            $table->string('tags')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->unsignedInteger('language_id')->nullable()->index();
            $table->unsignedBigInteger('parent_id')->default(0)->index();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category')->references('id')->on('blog_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
