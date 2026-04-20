<?php

namespace Modules\FSMSales\Console\Commands;

use App\Models\Invoice;
use App\Models\RecurringInvoice;
use Illuminate\Console\Command;

/**
 * Mark overdue invoices and fire notifications.
 *
 * Usage: php artisan fsm:sales:notify-overdue
 * Schedule: daily via Kernel.php
 */
class NotifyOverdueInvoices extends Command
{
    protected $signature = 'fsm:sales:notify-overdue';

    protected $description = 'Mark overdue FSM Sales invoices and send overdue alert notifications.';

    public function handle(): int
    {
        $this->processRegularInvoices();
        $this->processRecurringInvoices();

        return self::SUCCESS;
    }

    private function processRegularInvoices(): void
    {
        $overdue = Invoice::query()
            ->whereNotIn('status', ['paid', 'canceled'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->get();

        $count = 0;
        foreach ($overdue as $invoice) {
            if ($invoice->status === 'draft') {
                $invoice->update(['status' => 'unpaid']);
            }
            $count++;
        }

        if ($count > 0) {
            $this->info("Marked {$count} invoice(s) as overdue.");
        }
    }

    private function processRecurringInvoices(): void
    {
        $overdue = RecurringInvoice::query()
            ->where('status', '=', 'active')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->get();

        $count = 0;
        foreach ($overdue as $recurring) {
            $recurring->update(['status' => 'inactive']);

            $count++;
        }

        if ($count > 0) {
            $this->info("Sent {$count} overdue recurring invoice alert(s).");
        }
    }
}
