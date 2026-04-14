<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('zoom_setting')) {
        Schema::table('zoom_setting', function (Blueprint $table) {
            if (!Schema::hasColumn('zoom_setting', 'secret_token')) {
                $table->string('secret_token')->nullable();
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
        Schema::table('zoom_setting', function (Blueprint $table) {
            $table->dropColumn(['secret_token']);

        });
    }
};
