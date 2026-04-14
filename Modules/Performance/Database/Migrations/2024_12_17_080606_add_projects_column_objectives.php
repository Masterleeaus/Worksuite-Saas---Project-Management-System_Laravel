<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('objectives')) {
        Schema::table('objectives', function (Blueprint $table) {
            if (!Schema::hasColumn('objectives', 'project_id')) {
                $table->unsignedInteger('project_id')->after('id')->nullable();
            }
            $table->foreign('project_id')->references('id')->on('projects')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('objectives')) {
        Schema::table('objectives', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            if (Schema::hasColumn('objectives', 'project_id')) {
                $table->dropColumn('project_id');
            }
        });
    }
    }

};
