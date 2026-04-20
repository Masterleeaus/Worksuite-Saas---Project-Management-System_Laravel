<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql' || !Schema::hasTable('employee_details')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE `employee_details` DROP FOREIGN KEY `employee_details_designation_id_foreign`;');
        } catch (\Exception $e) {
            // FK may not exist
        }
        try {
            DB::statement('ALTER TABLE `employee_details` ADD CONSTRAINT `employee_details_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;');
        } catch (\Exception $e) {
            // FK may already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }

};
