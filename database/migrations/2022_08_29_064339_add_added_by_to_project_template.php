<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        if (!Schema::hasColumn('project_templates', 'added_by')) {
            Schema::table('project_templates', function (Blueprint $table) {
                $table->integer('added_by')->default(1);
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
        Schema::table('project_templates', function (Blueprint $table) {
            $table->dropColumn('added_by');
        });
    }

};
