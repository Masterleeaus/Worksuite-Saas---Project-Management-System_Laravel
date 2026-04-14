<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoverImageInProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('providers')) {
        Schema::table('providers', function (Blueprint $table) {
            if (!Schema::hasColumn('providers', 'cover_image')) {
                $table->string('cover_image',191)->nullable();
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
        if (Schema::hasTable('providers')) {
        Schema::table('providers', function (Blueprint $table) {
            if (Schema::hasColumn('providers', 'cover_image')) {
                $table->dropColumn('cover_image');
            }
        });
    }
    }
}
