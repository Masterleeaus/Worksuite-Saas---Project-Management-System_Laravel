<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer("contact_by_id");
            $table->integer("route_id");
            $table->integer("supervisor_id");
            $table->integer("status_id")->default(1);

            $table->string("account_name")->default(null)->nullable();
            $table->string("customer_name")->default(null)->nullable();
            
            $table->string("official_email")->default(null)->nullable();
            $table->string("official_phone")->default(null)->nullable();
            $table->string("official_fax")->default(null)->nullable();
            $table->string("official_website")->default(null)->nullable();
            $table->string("erp_code")->default(null)->nullable();
            $table->string("sfa_code")->default(null)->nullable();
            $table->double("latitude")->default(0.0)->nullable();
            $table->double("longitude")->default(0.0)->nullable();
            $table->string("location_name")->default(null)->nullable();
            $table->string("address")->default(null)->nullable();
            $table->string("payment_type")->default(null)->nullable();
            $table->double("credit_limit")->default(0.0)->nullable();
            $table->integer("credit_days")->default(0)->nullable();

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
        Schema::dropIfExists('customers');
    }
}
