<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToColReadableIdInReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('reviews')) {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'readable_id')) {
                $table->bigInteger('readable_id')->after('id');
            }
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('reviews')) {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'readable_id')) {
                $table->dropColumn('readable_id');
            }
        });
    }
    }
}
