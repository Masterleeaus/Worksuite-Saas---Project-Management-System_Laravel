<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Core overlay mode: EInvoice extends the existing core `invoices` table.
        // Intentionally left as a no-op to avoid creating duplicate invoice tables.
    }

    public function down(): void
    {
        // No-op: this migration no longer creates a table.
    }
};
