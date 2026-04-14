<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColAddToServicesTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('services') || Schema::hasColumn('services', 'deleted_at')) {
            return;
        }

        $afterColumn = Schema::hasColumn('services', 'avg_rating') ? 'avg_rating' : null;

        Schema::table('services', function (Blueprint $table) use ($afterColumn) {
            $column = $table->softDeletes();
            if ($afterColumn) {
                $column->after($afterColumn);
            }
        });
    }

    public function down()
    {
        if (! Schema::hasTable('services') || ! Schema::hasColumn('services', 'deleted_at')) {
            return;
        }

        Schema::table('services', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
