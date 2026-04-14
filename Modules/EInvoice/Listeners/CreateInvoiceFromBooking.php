<?php

namespace Modules\EInvoice\Listeners;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\RecurringInvoice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\BookingModule\Events\BookingCompleted;

class CreateInvoiceFromBooking
{
    public function handle(BookingCompleted $event): void
    {
        $booking = $event->booking;

        if ($booking->booking_status !== 'completed' || $booking->invoice_generated || !is_null($booking->generated_invoice_id)) {
            return;
        }

        DB::transaction(function () use ($booking): void {
            $lineAmount = (float) ($booking->task_budget ?? 0);

            $invoice = Invoice::create([
                'client_id'       => $booking->project?->client_id,
                'project_id'      => $booking->project_id,
                'company_id'      => $booking->company_id,
                'issue_date'      => Carbon::now(),
                'due_date'        => Carbon::now()->addDays(14),
                'status'          => 'unpaid',
                'sub_total'       => $lineAmount,
                'total'           => $lineAmount,
                'due_amount'      => $lineAmount,
                'booking_id'      => $booking->booking_id ?? $booking->id,
                'job_date'        => optional($booking->due_date)->toDateString() ?? Carbon::now()->toDateString(),
                'service_address' => $booking->service_address,
                'service_type'    => $booking->service_type,
                'auto_generated'  => true,
                'note'            => 'Auto-generated cleaning invoice for booking #' . ($booking->booking_id ?? $booking->id),
                'added_by'        => $booking->added_by ?? $booking->created_by,
                'created_by'      => $booking->created_by,
            ]);

            InvoiceItems::create([
                'invoice_id'    => $invoice->id,
                'item_name'     => ucfirst(str_replace('_', ' ', (string) ($booking->service_type ?: 'Cleaning service'))),
                'item_summary'  => $booking->service_address,
                'type'          => 'item',
                'quantity'      => 1,
                'unit_price'    => $lineAmount,
                'amount'        => $lineAmount,
            ]);

            if (!empty($booking->description)) {
                InvoiceItems::create([
                    'invoice_id'   => $invoice->id,
                    'item_name'    => 'Add-ons',
                    'item_summary' => $booking->description,
                    'type'         => 'item',
                    'quantity'     => 1,
                    'unit_price'   => 0,
                    'amount'       => 0,
                ]);
            }

            $rotation = match ($booking->frequency) {
                'weekly' => 'weekly',
                'fortnightly' => 'bi-weekly',
                'monthly' => 'monthly',
                default => null,
            };

            if (!is_null($rotation) && Schema::hasTable('invoice_recurring')) {
                $recurringPayload = [
                    'company_id'        => $booking->company_id,
                    'currency_id'       => $invoice->currency_id,
                    'project_id'        => $booking->project_id,
                    'client_id'         => $booking->project?->client_id,
                    'created_by'        => $booking->created_by,
                    'added_by'          => $booking->added_by ?? $booking->created_by,
                    'issue_date'        => Carbon::now()->toDateString(),
                    'due_date'          => Carbon::now()->addDays(14)->toDateString(),
                    'sub_total'         => $lineAmount,
                    'total'             => $lineAmount,
                    'rotation'          => $rotation,
                    'billing_cycle'     => 1,
                    'status'            => 'active',
                    'note'              => 'Recurring setup for booking #' . ($booking->booking_id ?? $booking->id),
                ];

                if (Schema::hasColumn('invoice_recurring', 'next_invoice_date')) {
                    $recurringPayload['next_invoice_date'] = Carbon::now()->toDateString();
                }

                RecurringInvoice::create($recurringPayload);
            }

            $booking->forceFill([
                'invoice_generated' => true,
                'generated_invoice_id' => $invoice->id,
            ])->saveQuietly();
        });
    }
}
