<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_brands', function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists('parent_brands');
    }
}
