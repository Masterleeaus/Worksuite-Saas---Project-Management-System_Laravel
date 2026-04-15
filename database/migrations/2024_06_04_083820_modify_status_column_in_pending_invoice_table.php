<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql' || !Schema::hasTable('invoices')) {
            return;
        }

        DB::statement("ALTER TABLE invoices CHANGE COLUMN status status ENUM('paid', 'unpaid', 'partial', 'canceled', 'draft', 'pending-confirmation') NOT NULL DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }

};
