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
        Schema::table('tbl_modules', function (Blueprint $table) {
            $table->tinyInteger('show_in_menu')->default(0)->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_modules', function (Blueprint $table) {
            $table->dropColumn('show_in_menu');
        });
    }
};
