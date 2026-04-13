<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('zoom_setting')) {
            return;
        }
        Schema::table('zoom_setting', function (Blueprint $table) {
            if (! Schema::hasColumn('zoom_setting', 'account_id')) {
                $table->string('account_id')->nullable();
            }
            if (! Schema::hasColumn('zoom_setting', 'meeting_client_id')) {
                $table->string('meeting_client_id')->nullable();
            }
            if (! Schema::hasColumn('zoom_setting', 'meeting_client_secret')) {
                $table->string('meeting_client_secret')->nullable();
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
        Schema::table('zoom_setting', function (Blueprint $table) {
            $table->dropColumn(['account_id', 'meeting_client_id', 'meeting_client_secret']);
        });
    }

};
