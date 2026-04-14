<?php

use Illuminate\Database\Migrations\Migration;
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

        $hasCurrencyForeignKey = collect(Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('salary_slips'))
            ->contains(fn ($foreignKey) => in_array('currency_id', $foreignKey->getLocalColumns(), true));

        Schema::table('salary_slips', function (Blueprint $table) use ($hasCurrencyForeignKey) {
            if ($hasCurrencyForeignKey) {
                $table->dropForeign(['currency_id']);
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

        $hasCurrencyForeignKey = collect(Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('salary_slips'))
            ->contains(fn ($foreignKey) => in_array('currency_id', $foreignKey->getLocalColumns(), true));

        Schema::table('salary_slips', function (Blueprint $table) use ($hasCurrencyForeignKey) {
            if (Schema::hasColumn('salary_slips', 'currency_id')) {
                if ($hasCurrencyForeignKey) {
                    $table->dropForeign(['currency_id']);
                }

                $table->dropColumn('currency_id');
            }
        });
    }
};
