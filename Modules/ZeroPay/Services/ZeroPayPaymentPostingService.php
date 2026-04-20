<?php

namespace Modules\ZeroPay\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\ZeroPay\Models\ZeroPayAttempt;

class ZeroPayPaymentPostingService
{
    public function postConfirmedAttempt(ZeroPayAttempt $attempt, array $payload = []): Payment
    {
        return DB::transaction(function () use ($attempt, $payload) {
            $attempt->refresh();
            $session = $attempt->session()->with('invoice')->firstOrFail();
            $invoice = $session->invoice;

            if (!$invoice instanceof Invoice) {
                throw new \RuntimeException('ZeroPay session is not linked to an invoice.');
            }

            $existingPayment = null;

            if ($attempt->processed_payment_id) {
                $existingPayment = Payment::query()->find($attempt->processed_payment_id);
            }

            if ($existingPayment) {
                return $existingPayment;
            }

            $transactionId = $payload['transaction_id'] ?? ('zeropay_' . $attempt->id . '_' . Str::lower(Str::random(8)));

            while (Payment::query()->where('transaction_id', $transactionId)->exists()) {
                $transactionId = 'zeropay_' . $attempt->id . '_' . Str::lower(Str::random(8));
            }

            $payment = new Payment();
            $payment->company_id = $session->company_id;
            $payment->project_id = $invoice->project_id;
            $payment->invoice_id = $invoice->id;
            $payment->order_id = $invoice->order_id;
            $payment->gateway = $attempt->method;
            $payment->transaction_id = $transactionId;
            $payment->event_id = 'zeropay_attempt_' . $attempt->id;
            $payment->currency_id = $invoice->currency_id;
            $payment->amount = (float) ($attempt->amount ?: $session->amount ?: $invoice->due_amount);
            $payment->paid_on = now();
            $payment->status = 'complete';
            $payment->remarks = $payload['remarks'] ?? 'Posted by ZeroPay confirmation flow';
            $payment->save();

            $attempt->processed_payment_id = $payment->id;
            $attempt->status = 'confirmed';
            $attempt->confirmed_at = now();
            $attempt->save();

            $totalPaid = (float) Payment::query()
                ->where('invoice_id', $invoice->id)
                ->where('status', 'complete')
                ->sum('amount');

            $due = max((float) $invoice->total - $totalPaid, 0.0);
            $invoice->due_amount = $due;
            $invoice->status = $due <= 0.0 ? 'paid' : ($due < (float) $invoice->total ? 'partial' : 'unpaid');
            $invoice->save();

            $session->status = 'paid';
            $session->paid_at = now();
            $session->save();

            return $payment;
        });
    }
}
