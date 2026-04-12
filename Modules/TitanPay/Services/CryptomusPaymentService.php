<?php

namespace Modules\TitanPay\Services;

use App\Models\Invoice;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Wraps the Cryptomus v1 REST API.
 *
 * Docs: https://doc.cryptomus.com/payments
 *
 * Required ENV:
 *   CRYPTOMUS_PAYMENT_API_KEY  — API key (from dashboard → API → Payment)
 *   CRYPTOMUS_MERCHANT_UUID    — Merchant UUID
 */
class CryptomusPaymentService
{
    private const BASE_URL = 'https://api.cryptomus.com/v1/';

    private string $apiKey;
    private string $merchantUuid;

    public function __construct()
    {
        $this->apiKey       = (string) config('titanpay.cryptomus.payment_api_key');
        $this->merchantUuid = (string) config('titanpay.cryptomus.merchant_uuid');
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Create a Cryptomus payment invoice.
     *
     * @param  Invoice    $invoice   WorkSuite invoice record
     * @param  string     $currency  Fiat currency code (e.g. 'USD')
     * @param  string|null $network  Optional crypto network ('ETH', 'TRX', …)
     * @return array{url: string, uuid: string, order_id: string}
     * @throws \RuntimeException
     */
    public function createPayment(Invoice $invoice, string $currency = 'USD', ?string $network = null): array
    {
        $this->assertConfigured();

        $orderId = 'inv_' . $invoice->id . '_' . time();

        $payload = [
            'amount'      => (string) number_format((float) $invoice->amountDue(), 2, '.', ''),
            'currency'    => strtoupper($currency),
            'order_id'    => $orderId,
            'url_return'  => route('titanpay.crypto.return', ['invoiceId' => $invoice->id]),
            'url_callback'=> route('titanpay.crypto.webhook'),
            'is_payment_multiple' => false,
            'lifetime'    => 3600,
        ];

        if ($network) {
            $payload['network'] = $network;
        }

        $response = $this->post('payment', $payload);

        return [
            'url'      => $response['result']['url']      ?? '',
            'uuid'     => $response['result']['uuid']     ?? '',
            'order_id' => $response['result']['order_id'] ?? $orderId,
        ];
    }

    /**
     * Verify and parse an incoming Cryptomus webhook payload.
     *
     * Returns the parsed payload array when the signature is valid,
     * throws \RuntimeException otherwise.
     *
     * @param  array  $payload  Raw POST data from Cryptomus webhook
     * @param  string $sign     Value of the 'sign' field in the payload
     * @throws \RuntimeException on signature mismatch
     */
    public function verifyWebhook(array $payload, string $sign): array
    {
        $webhookSecret = (string) config('titanpay.cryptomus.webhook_secret');

        // NOTE: MD5 is mandated by the Cryptomus v1 API signature scheme (their protocol constraint).
        // Signature: md5( base64_encode(json_encode($data_without_sign)) + api_key )
        $dataWithoutSign = $payload;
        unset($dataWithoutSign['sign']);

        $expected = md5(base64_encode(json_encode($dataWithoutSign)) . ($webhookSecret ?: $this->apiKey));

        if (!hash_equals($expected, $sign)) {
            throw new \RuntimeException('Cryptomus webhook signature mismatch.');
        }

        return $payload;
    }

    /**
     * Check payment status by Cryptomus payment UUID.
     *
     * @return array{status: string, payment_status: string, amount: string}
     */
    public function checkStatus(string $paymentUuid): array
    {
        $this->assertConfigured();

        $response = $this->post('payment/info', ['uuid' => $paymentUuid]);

        return $response['result'] ?? [];
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * POST to Cryptomus API.
     *
     * @throws \RuntimeException
     */
    private function post(string $endpoint, array $data): array
    {
        $json    = json_encode($data);
        $sign    = md5(base64_encode($json) . $this->apiKey);

        try {
            $response = Http::withHeaders([
                'merchant' => $this->merchantUuid,
                'sign'     => $sign,
                'Content-Type' => 'application/json',
            ])->post(self::BASE_URL . $endpoint, $data);

            $response->throw();

            return $response->json() ?? [];
        } catch (RequestException $e) {
            Log::error('TitanPay/Cryptomus API error', [
                'endpoint' => $endpoint,
                'status'   => $e->response->status(),
                'body'     => $e->response->body(),
            ]);

            throw new \RuntimeException(
                'Cryptomus API request failed: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    private function assertConfigured(): void
    {
        if (empty($this->apiKey) || empty($this->merchantUuid)) {
            throw new \RuntimeException('Cryptomus API key / Merchant UUID not configured in TitanPay settings.');
        }
    }
}
