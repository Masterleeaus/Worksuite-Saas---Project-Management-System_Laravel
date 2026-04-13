<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddresType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('user_addresses')) {
            return;
        }
        Schema::table('user_addresses', function (Blueprint $table) {
            if (! Schema::hasColumn('user_addresses', 'address_type')) {
                $table->string('address_type')->nullable();
            }
            if (! Schema::hasColumn('user_addresses', 'contact_person_name')) {
                $table->string('contact_person_name')->nullable();
            }
            if (! Schema::hasColumn('user_addresses', 'contact_person_number')) {
                $table->string('contact_person_number')->nullable();
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
        Schema::table('', function (Blueprint $table) {

        });
    }
}
