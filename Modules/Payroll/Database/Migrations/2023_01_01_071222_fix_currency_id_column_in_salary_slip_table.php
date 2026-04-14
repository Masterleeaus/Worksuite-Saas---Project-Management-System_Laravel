<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('salary_slips')) {
            return;
        }

        // SQLite cannot drop foreign keys during table alteration.
        if (DB::getDriverName() === 'sqlite' || ! Schema::hasColumn('salary_slips', 'currency_id')) {
            return;
        }

        Schema::table('salary_slips', function (Blueprint $table) {
            try {
                $table->dropForeign(['currency_id']);
            } catch (\Throwable $e) {
                // Ignore missing/legacy constraint names and continue to ensure target FK exists.
            }

            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('salary_slips') || DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('salary_slips', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
        });
    }
};
