<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer("major_category_id");
            $table->integer("sub_major_category_id");
            $table->integer("sub_category_id");
            $table->integer("parent_brand_id");
            $table->integer("brand_id");
            $table->integer("uom_type_id");
            $table->integer("uom_id");
            $table->string("erp_code")->default(null)->nullable(true);
            $table->string("image")->default(null)->nullable(true);
            
            $table->string("sfa_code")->default(null)->nullable(true);
            $table->string("name")->default(null)->nullable(true);
            $table->string("description")->default(null)->nullable(true);
            $table->string("sku")->default(null)->nullable(true);
            $table->double("cost_price")->default(0.0);
            $table->double("purchase_price")->default(0.0);
            $table->double("product_msrp")->default(0.0);
            $table->double("whole_sale_price")->default(0.0);
            $table->double("maximum_whole_sale_price")->default(0.0);
            $table->integer("stock")->default(0);
            $table->string("color")->default(null)->nullable(true);
            $table->integer("status_id")->default(1);
            $table->integer("created_by")->nullable(true);
            $table->integer("updated_by")->nullable(true);
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
        Schema::dropIfExists('products');
    }
}
