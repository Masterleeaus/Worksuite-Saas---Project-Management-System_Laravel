<?php

namespace Modules\TitanPay\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\Invoice;
use App\Traits\MakePaymentTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\TitanPay\Services\CryptomusPaymentService;

class CryptoPaymentController extends AccountBaseController
{
    use MakePaymentTrait;

    public function __construct(
        private readonly CryptomusPaymentService $cryptomus
    ) {
        parent::__construct();
        $this->pageTitle = 'TitanPay — Crypto Payment';
    }

    /**
     * Initiate a Cryptomus checkout for an invoice.
     * GET /account/titanpay/crypto/{invoiceId}/pay
     */
    public function pay(int $invoiceId)
    {
        $invoice  = Invoice::findOrFail($invoiceId);
        $currency = $invoice->currency?->currency_code ?? config('titanpay.cryptomus.currency', 'USD');
        $network  = config('titanpay.cryptomus.network') ?: null;

        try {
            $result = $this->cryptomus->createPayment($invoice, $currency, $network);

            return redirect()->away($result['url']);
        } catch (\Throwable $e) {
            Log::error('TitanPay/Crypto pay error', ['invoice' => $invoiceId, 'error' => $e->getMessage()]);

            return back()->withErrors(['error' => 'Could not create crypto payment: ' . $e->getMessage()]);
        }
    }

    /**
     * Client return URL after Cryptomus checkout (GET).
     * GET /account/titanpay/crypto/return/{invoiceId}
     */
    public function returnUrl(int $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        return view('titanpay::payment.crypto-return', compact('invoice'));
    }

    /**
     * Cryptomus webhook handler (POST, no CSRF).
     * POST /titanpay/crypto/webhook
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        $sign    = $payload['sign'] ?? '';

        try {
            $verified = $this->cryptomus->verifyWebhook($payload, $sign);
        } catch (\Throwable $e) {
            Log::warning('TitanPay/Crypto webhook signature invalid', ['ip' => $request->ip()]);

            return response()->json(['error' => 'Invalid signature.'], 400);
        }

        $status  = $verified['status']       ?? '';
        $orderId = $verified['order_id']     ?? '';

        // order_id format: "inv_{invoiceId}_{timestamp}"
        if (str_starts_with($orderId, 'inv_')) {
            $invoiceId = (int) explode('_', $orderId)[1];
            $invoice   = Invoice::find($invoiceId);

            if ($invoice && in_array($status, ['paid', 'paid_over', 'wrong_amount_waiting', 'check'], true)) {
                if ($invoice->status !== 'paid') {
                    $invoice->status = 'paid';
                    $invoice->save();

                    // Record payment via core trait
                    $this->makePayment(
                        'cryptomus',
                        (float) ($verified['amount'] ?? $invoice->total),
                        $invoice,
                        $verified['uuid'] ?? $orderId,
                        'completed'
                    );
                }
            }
        }

        return response()->json(['result' => 'ok']);
    }
}
