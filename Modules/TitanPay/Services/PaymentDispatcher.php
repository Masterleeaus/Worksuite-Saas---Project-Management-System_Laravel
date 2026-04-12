<?php

namespace Modules\TitanPay\Services;

use App\Models\Invoice;

/**
 * Routes a payment intent to the appropriate gateway.
 *
 * Supported gateway keys:
 *   - 'cryptomus'  → crypto payment via Cryptomus API
 *   - 'stripe'     → delegates to core Stripe controller URL
 *   - 'paypal'     → delegates to core PayPal controller URL
 *   - 'offline'    → manual / bank-transfer payment
 *   - 'any'        → shows the TitanPay selection page
 */
class PaymentDispatcher
{
    public function __construct(
        private readonly CryptomusPaymentService $cryptomus
    ) {}

    /**
     * Dispatch a payment for an invoice via the given gateway.
     *
     * Returns an array with:
     *   - 'redirect_url' — where to send the client
     *   - 'gateway'      — resolved gateway key
     *   - 'data'         — gateway-specific payload (may be empty)
     *
     * @throws \InvalidArgumentException on unknown gateway
     * @throws \RuntimeException on gateway API failure
     */
    public function dispatch(Invoice $invoice, string $gateway = 'any'): array
    {
        return match ($gateway) {
            'cryptomus' => $this->dispatchCryptomus($invoice),
            'stripe'    => $this->dispatchCore($invoice, 'stripe'),
            'paypal'    => $this->dispatchCore($invoice, 'paypal'),
            'offline'   => $this->dispatchOffline($invoice),
            'any'       => $this->dispatchSelection($invoice),
            default     => throw new \InvalidArgumentException("Unknown TitanPay gateway: {$gateway}"),
        };
    }

    // -------------------------------------------------------------------------

    private function dispatchCryptomus(Invoice $invoice): array
    {
        $currency = $invoice->currency?->currency_code ?? config('titanpay.cryptomus.currency', 'USD');
        $network  = config('titanpay.cryptomus.network') ?: null;

        $result = $this->cryptomus->createPayment($invoice, $currency, $network);

        return [
            'redirect_url' => $result['url'],
            'gateway'      => 'cryptomus',
            'data'         => $result,
        ];
    }

    private function dispatchCore(Invoice $invoice, string $gateway): array
    {
        // Delegate to the core PaymentModule route when it exists
        $routeName = "paymentmodule.{$gateway}.pay";

        try {
            $url = route($routeName, ['invoice' => $invoice->id, 'type' => 'invoice']);
        } catch (\Throwable) {
            $url = url("account/payment/{$gateway}/pay?type=invoice&id={$invoice->id}");
        }

        return [
            'redirect_url' => $url,
            'gateway'      => $gateway,
            'data'         => [],
        ];
    }

    private function dispatchOffline(Invoice $invoice): array
    {
        return [
            'redirect_url' => route('titanpay.payment.show', ['token' => '']) . '?gateway=offline&invoice=' . $invoice->id,
            'gateway'      => 'offline',
            'data'         => [],
        ];
    }

    private function dispatchSelection(Invoice $invoice): array
    {
        return [
            'redirect_url' => route('titanpay.payment.show', ['token' => 'select']) . '?invoice=' . $invoice->id,
            'gateway'      => 'any',
            'data'         => [],
        ];
    }
}
