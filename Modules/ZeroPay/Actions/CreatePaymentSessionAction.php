<?php

namespace Modules\ZeroPay\Actions;

use App\Models\Invoice;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\ZeroPay\Events\PaymentSessionCreated;
use Modules\ZeroPay\Models\ZeroPaySession;

class CreatePaymentSessionAction
{
    public function execute(array $payload): ZeroPaySession
    {
        $invoice = Invoice::query()->findOrFail((int) $payload['invoice_id']);

        $session = ZeroPaySession::query()->create([
            'company_id' => (int) $invoice->company_id,
            'invoice_id' => (int) $invoice->id,
            'client_id' => (int) $invoice->client_id,
            'booking_id' => isset($payload['booking_id']) ? (int) $payload['booking_id'] : null,
            'source_type' => $payload['source_type'] ?? 'invoice',
            'source_reference' => $payload['source_reference'] ?? null,
            'public_token' => Str::lower(Str::random(64)),
            'status' => 'sent',
            'selected_method' => null,
            'amount' => (float) ($payload['amount'] ?? $invoice->due_amount ?? $invoice->total),
            'currency_id' => (int) $invoice->currency_id,
            'expires_at' => now()->addDays(7),
            'created_by' => auth()->id(),
            'meta' => Arr::only($payload, ['notes']),
        ]);

        $session->payment_link = route('zeropay.session.show', $session->public_token);
        $session->qr_payload = $session->payment_link;
        $session->save();

        event(new PaymentSessionCreated($session));

        return $session;
    }
}
