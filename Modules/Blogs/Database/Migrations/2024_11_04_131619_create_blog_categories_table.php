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
        if (Schema::hasTable('blog_categories')) {
            return;
        }
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable()->index();
            $table->string('name');
            $table->string('slug');
            $table->boolean('status')->default(true);
            $table->unsignedInteger('language_id')->nullable()->index();
            $table->unsignedBigInteger('parent_id')->default(0)->index();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
