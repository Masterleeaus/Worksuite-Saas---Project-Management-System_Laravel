<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            } catch (QueryException $e) {
                $message = $e->getMessage();

                if (! str_contains($message, 'check that column/key exists') && ! str_contains($message, 'Cannot drop index')) {
                    throw $e;
                }
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
