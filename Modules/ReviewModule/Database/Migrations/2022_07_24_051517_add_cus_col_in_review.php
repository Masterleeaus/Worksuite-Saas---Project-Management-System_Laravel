<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCusColInReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('reviews')) {
            return;
        }
        Schema::table('reviews', function (Blueprint $table) {
            if (! Schema::hasColumn('reviews', 'customer_id')) {
                $table->foreignUuid('customer_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
}
