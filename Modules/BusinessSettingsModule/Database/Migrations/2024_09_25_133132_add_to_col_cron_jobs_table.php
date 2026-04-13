<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToColCronJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('cron_jobs')) {
            return;
        }
        Schema::table('cron_jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('cron_jobs', 'type')) {
                $table->string('type')->nullable();
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
        Schema::table('cron_jobs', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
