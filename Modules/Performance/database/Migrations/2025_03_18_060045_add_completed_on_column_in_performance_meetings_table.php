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
        Schema::table('performance_meetings', function (Blueprint $table) {
            $table->datetime('completed_on')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('performance_meetings')) {
        Schema::table('performance_meetings', function (Blueprint $table) {
            if (Schema::hasColumn('performance_meetings', 'completed_on')) {
                $table->dropColumn('completed_on');
            }
        });
    }
    }

};
