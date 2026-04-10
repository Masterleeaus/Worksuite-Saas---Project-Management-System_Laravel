<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskHasProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_has_products', function (Blueprint $table) {
            $table->id();           
            $table->integer("product_id");
            $table->integer("task_id");
            $table->integer("qty");
            $table->double("unit_price");
            $table->string("description");
            $table->double("total_price");
            $table->integer("created_by");
            $table->timestamp("deleted_at")->default(null)->nullable();
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
        Schema::dropIfExists('task_has_products');
    }
}
