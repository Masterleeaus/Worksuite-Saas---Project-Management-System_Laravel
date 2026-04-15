<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql' || !Schema::hasTable('assets')) {
            return;
        }

        DB::statement("ALTER TABLE assets CHANGE COLUMN status status ENUM('lent', 'available', 'non-functional', 'lost', 'damaged','under-maintenance') NOT NULL DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('assets');
    }

};
