<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('posts')) {
            return;
        }
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'is_checked')) {
                $table->boolean('is_checked')->default(0)->after('is_booked')->nullable();
            }
            if (! Schema::hasColumn('posts', 'zone_id')) {
                $table->foreignUuid('zone_id')->after('service_address_id')->nullable();
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
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('is_checked');
            $table->dropColumn('zone_id');
        });
    }
}
