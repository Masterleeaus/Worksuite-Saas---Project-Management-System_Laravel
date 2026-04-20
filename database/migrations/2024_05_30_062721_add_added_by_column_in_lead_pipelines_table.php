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
        if (Schema::hasTable('lead_pipelines') && !Schema::hasColumn('lead_pipelines', 'added_by')) {
            Schema::table('lead_pipelines', function (Blueprint $table) {
                $table->integer('added_by')->nullable()->default(null);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lead_pipelines') && Schema::hasColumn('lead_pipelines', 'added_by')) {
            Schema::table('lead_pipelines', function (Blueprint $table) {
                $table->dropColumn('added_by');
            });
        }
    }

};
