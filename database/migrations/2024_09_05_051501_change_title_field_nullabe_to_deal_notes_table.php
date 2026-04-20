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
        if (!Schema::hasTable('deal_notes') || !Schema::hasColumn('deal_notes', 'title')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'sqlite' && !Schema::hasTable('deals')) {
            return;
        }

        Schema::table('deal_notes', function (Blueprint $table) {
            $table->string('title', 191)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('deal_notes') || !Schema::hasColumn('deal_notes', 'title')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'sqlite' && !Schema::hasTable('deals')) {
            return;
        }

        Schema::table('deal_notes', function (Blueprint $table) {
            $table->string('title', 191)->nullable(false)->change();
        });
    }

};
