<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('orders') || Schema::hasColumn('orders', 'due_amount')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->double('due_amount')->default(0);
        });
    }

    public function down(): void
    {
        // Intentionally left blank to keep rollback safe across sqlite/mysql validation paths.
    }
};
