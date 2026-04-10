<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubMajorCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_major_categories', function (Blueprint $table) {
            $table->id();
            $table->integer("major_category_id")->nullable(false);
            $table->string("name_en");
            $table->string("image")->nullable(true)->default(null);
            $table->integer("created_by")->nullable(true);
            $table->timestamp("deleted_at")->default(null)->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_major_categories');
    }
}
