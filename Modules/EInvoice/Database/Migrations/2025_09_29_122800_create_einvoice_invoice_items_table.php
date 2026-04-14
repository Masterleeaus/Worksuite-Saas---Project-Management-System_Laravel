<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Core overlay mode: EInvoice uses core `invoice_items` via App\Models\InvoiceItems.
        // Intentionally left as a no-op to avoid duplicate item tables.
    }

    public function down(): void
    {
        // No-op: this migration no longer creates a table.
    }
};
