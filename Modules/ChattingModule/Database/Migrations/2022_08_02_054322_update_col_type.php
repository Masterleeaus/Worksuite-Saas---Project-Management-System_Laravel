<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('channel_lists')) {
            return;
        }
        if (!Schema::hasColumn('channel_lists', 'reference_id')) {
            return;
        }

        Schema::table('channel_lists', function (Blueprint $table) {
            $table->uuid('reference_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('channel_lists')) {
            return;
        }
        return;
    }
}
