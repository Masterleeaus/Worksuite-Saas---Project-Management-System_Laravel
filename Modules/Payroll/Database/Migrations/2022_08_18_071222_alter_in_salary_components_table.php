<?php

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
        if (\DB::getDriverName() === 'sqlite' || !\Schema::hasTable('salary_components')) {
            return;
        }

        DB::statement("ALTER TABLE `salary_components` CHANGE `value_type` `value_type` ENUM('fixed','percent','basic_percent','variable') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
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
