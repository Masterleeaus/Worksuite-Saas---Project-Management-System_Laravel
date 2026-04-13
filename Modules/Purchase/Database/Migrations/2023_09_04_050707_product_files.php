<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        if (! Schema::hasTable('product_files')) {
            return;
        }
        Schema::table('product_files', function(Blueprint $table){
            if (! Schema::hasColumn('product_files', 'default_status')) {
                $table->boolean('default_status')->default(false)->nullable();
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
        //
    }

};
