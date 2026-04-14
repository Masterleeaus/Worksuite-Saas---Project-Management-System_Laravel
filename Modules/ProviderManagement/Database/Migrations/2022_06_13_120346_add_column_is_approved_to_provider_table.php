<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsApprovedToProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('providers')) {
            return;
        }
        if (!Schema::hasColumn('providers', 'is_approved')) {
            Schema::table('providers', function (Blueprint $table) {
                $table->boolean('is_approved')->default(0);
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
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
}
