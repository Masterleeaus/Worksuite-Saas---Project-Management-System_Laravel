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
        if (! Schema::hasColumn('zoom_setting', 'purchase_code')) {
            if (Schema::hasTable('zoom_setting')) {
        Schema::table('zoom_setting', function (Blueprint $table) {
                if (!Schema::hasColumn('zoom_setting', 'purchase_code')) {
                    $table->string('purchase_code')->nullable();
                }
                if (!Schema::hasColumn('zoom_setting', 'supported_until')) {
                    $table->timestamp('supported_until')->nullable();
                }
        });
    }
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
            $table->dropColumn(['purchase_code', 'supported_until']);
        });
    }
};
