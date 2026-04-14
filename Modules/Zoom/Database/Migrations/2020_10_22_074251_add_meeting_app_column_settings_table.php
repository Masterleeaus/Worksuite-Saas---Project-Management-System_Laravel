<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        if (Schema::hasTable('zoom_setting')) {
        Schema::table('zoom_setting', function (Blueprint $table) {
            if (!Schema::hasColumn('zoom_setting', 'meeting_app')) {
                $table->string('meeting_app')->default('in_app');
            }
        });
    }

        if (Schema::hasTable('zoom_meetings')) {
        Schema::table('zoom_meetings', function (Blueprint $table) {
            if (!Schema::hasColumn('zoom_meetings', 'password')) {
                $table->string('password')->nullable();
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
        Schema::table('zoom_setting', function (Blueprint $table) {
            $table->dropColumn(['meeting_app']);
        });

        Schema::table('zoom_meetings', function (Blueprint $table) {
            $table->dropColumn(['password']);
        });
    }
};
