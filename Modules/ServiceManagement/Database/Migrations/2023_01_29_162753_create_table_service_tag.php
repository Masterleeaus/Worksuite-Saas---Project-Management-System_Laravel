<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableServiceTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('service_tag')) {
            return;
        }
        Schema::create('service_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->uuid('service_id');
            $table->uuid('tag_id');

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
        Schema::dropIfExists('');
    }
}
