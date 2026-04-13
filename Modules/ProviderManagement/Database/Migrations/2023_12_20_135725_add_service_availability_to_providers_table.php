<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceAvailabilityToProvidersTable extends Migration
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
        Schema::table('providers', function (Blueprint $table) {
            if (! Schema::hasColumn('providers', 'service_availability')) {
                $table->boolean('service_availability')->default(1)->nullable();
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
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn('service_availability');
        });
    }
}
