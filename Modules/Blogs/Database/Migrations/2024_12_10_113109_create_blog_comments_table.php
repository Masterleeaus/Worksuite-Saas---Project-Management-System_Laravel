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
        if (Schema::hasTable('blog_comments')) {
            return;
        }
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('post_id')->index();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('email');
            $table->string('image')->nullable();
            $table->text('comment');
            $table->dateTime('comment_date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('post_id')->references('id')->on('blog_posts')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};
