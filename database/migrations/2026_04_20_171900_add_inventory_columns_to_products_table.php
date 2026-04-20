<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock_quantity')) {
                $table->integer('stock_quantity')->nullable();
            }

            if (!Schema::hasColumn('products', 'reorder_point')) {
                $table->integer('reorder_point')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Intentionally left blank to keep rollback safe across sqlite/mysql validation paths.
    }
};
