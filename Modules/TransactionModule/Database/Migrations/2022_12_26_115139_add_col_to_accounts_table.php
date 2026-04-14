<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('accounts')) {
        Schema::table('accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('accounts', 'total_expense')) {
                $table->decimal('total_expense',24,2)->default(0)->after('total_withdrawn');
            }
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('accounts')) {
        Schema::table('accounts', function (Blueprint $table) {
            if (Schema::hasColumn('accounts', 'total_expense')) {
                $table->dropColumn('total_expense');
            }
        });
    }
    }
}
