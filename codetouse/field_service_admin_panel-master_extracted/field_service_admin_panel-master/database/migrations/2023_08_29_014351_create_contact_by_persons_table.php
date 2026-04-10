<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactByPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_by_persons', function (Blueprint $table) {
            $table->id();
            $table->string("name")->default(null)->nullable(true);
            $table->string("mobile")->default(null)->nullable(true);
            $table->string("official_number")->default(null)->nullable(true);
            $table->string("official_email")->default(null)->nullable(true);
            $table->string("personal_email")->default(null)->nullable(true);
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
        Schema::dropIfExists('contact_by_persons');
    }
}
