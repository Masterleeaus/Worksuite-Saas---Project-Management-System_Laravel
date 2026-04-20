<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (
            DB::connection()->getDriverName() === 'sqlite'
            || !Schema::hasTable('tasks')
            || !Schema::hasColumn('tasks', 'created_by')
        ) {
            return;
        }

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_created_by_foreign');
            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite' || !Schema::hasTable('tasks')) {
            return;
        }

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_created_by_foreign');
        });
    }
};
