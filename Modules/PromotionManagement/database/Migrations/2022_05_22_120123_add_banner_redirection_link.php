<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBannerRedirectionLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function ($table) {
            $table->string('redirect_link', 191)->after('resource_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('banners')) {
        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'redirect_link')) {
                $table->dropColumn('redirect_link');
            }
        });
    }
    }
}
