<?php

namespace Modules\TitanPay\Services;

use App\Models\Invoice;
use Illuminate\Support\Str;
use Modules\TitanPay\Models\PaymentLink;

/**
 * Generates signed, expiring payment URLs for invoices.
 * Links can be opened without a WorkSuite login.
 */
class PaymentLinkService
{
    /**
     * Generate (or retrieve an existing active) payment link for an invoice.
     *
     * @param Invoice $invoice
     * @param string  $expiry  Relative date string accepted by Carbon::parse(), e.g. '+30 days'
     * @param string  $gateway Preferred gateway ('any', 'stripe', 'cryptomus', …)
     */
    public function generate(Invoice $invoice, string $expiry = '+30 days', string $gateway = 'any'): PaymentLink
    {
        // Re-use an existing active link for the same invoice + gateway
        $existing = PaymentLink::query()
            ->where('invoice_id', $invoice->id)
            ->where('gateway', $gateway)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($existing) {
            return $existing;
        }

        return PaymentLink::create([
            'company_id'  => $invoice->company_id,
            'invoice_id'  => $invoice->id,
            'token'       => $this->makeToken(),
            'gateway'     => $gateway,
            'expires_at'  => now()->modify($expiry),
            'status'      => 'active',
        ]);
    }

    /**
     * Look up a payment link by its public token.
     * Returns null if not found, expired, or already used.
     */
    public function findValid(string $token): ?PaymentLink
    {
        $link = PaymentLink::where('token', $token)->first();

        if (!$link || !$link->isValid()) {
            return null;
        }

        return $link;
    }

    /**
     * Build the public URL for a PaymentLink record.
     */
    public function url(PaymentLink $link): string
    {
        return url('/pay/' . $link->token);
    }

    // -------------------------------------------------------------------------

    private function makeToken(): string
    {
        return Str::random(48);
    }
}
