<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribeNewslettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('subscribe_newsletters')) {
            return;
        }
        Schema::create('subscribe_newsletters', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('email')->unique();
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
        Schema::dropIfExists('subscribe_newsletters');
    }
}
