<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('menus')) {
            return;
        }

        if (!Schema::hasColumn('menus', 'parent_id')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('id');
                $table->foreign('parent_id')
                    ->references('id')->on('menus')
                    ->onDelete('set null')->onUpdate('cascade');
            });
        }

        if (!Schema::hasColumn('menus', 'sort_order')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->unsignedSmallInteger('sort_order')->default(0)->after('parent_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('menus')) {
            return;
        }

        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasColumn('menus', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
            if (Schema::hasColumn('menus', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};
