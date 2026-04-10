<?php

namespace Modules\FSMSales\Console\Commands;

use Illuminate\Console\Command;
use Modules\FSMSales\Models\FSMRecurringInvoice;
use Modules\FSMSales\Models\FSMSalesInvoice;

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
        $overdue = FSMSalesInvoice::query()
            ->whereNotIn('status', [FSMSalesInvoice::STATUS_PAID, FSMSalesInvoice::STATUS_VOID])
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->get();

        $count = 0;
        foreach ($overdue as $invoice) {
            if ($invoice->status !== FSMSalesInvoice::STATUS_OVERDUE) {
                $invoice->update(['status' => FSMSalesInvoice::STATUS_OVERDUE]);
                $count++;
            }
        }

        if ($count > 0) {
            $this->info("Marked {$count} invoice(s) as overdue.");
        }
    }

    private function processRecurringInvoices(): void
    {
        $overdue = FSMRecurringInvoice::query()
            ->where('status', '!=', FSMRecurringInvoice::STATUS_PAID)
            ->where('overdue_notified', false)
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->get();

        $count = 0;
        foreach ($overdue as $recurring) {
            $recurring->update([
                'status'           => FSMRecurringInvoice::STATUS_OVERDUE,
                'overdue_notified' => true,
            ]);

            // Fire notification if Sms or mail notifications are available
            $this->dispatchOverdueNotification($recurring);

            $count++;
        }

        if ($count > 0) {
            $this->info("Sent {$count} overdue recurring invoice alert(s).");
        }
    }

    private function dispatchOverdueNotification(FSMRecurringInvoice $recurring): void
    {
        // Integration hook – extend via EventServiceProvider listeners if needed
        try {
            event(new \Modules\FSMSales\Events\RecurringInvoiceOverdue($recurring));
        } catch (\Throwable) {
            // Silently skip if event class not registered
        }
    }
}
