<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsUpdatedToAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('advertisements')) {
            return;
        }
        Schema::table('advertisements', function (Blueprint $table) {
            if (! Schema::hasColumn('advertisements', 'is_updated')) {
                $table->boolean('is_updated')->default(0)->nullable();
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
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn('is_updated');
        });
    }
}
