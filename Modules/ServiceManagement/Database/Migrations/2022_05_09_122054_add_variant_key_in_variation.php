<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVariantKeyInVariation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('variations')) {
            return;
        }
        Schema::table('variations', function (Blueprint $table) {
            if (! Schema::hasColumn('variations', 'variant_key')) {
                $table->string('variant_key',191)->after('variant')->nullable();
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
        Schema::table('variations', function (Blueprint $table) {

        });
    }
}
