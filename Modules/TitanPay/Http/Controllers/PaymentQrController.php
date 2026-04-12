<?php

namespace Modules\TitanPay\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Modules\TitanPay\Models\PaymentQrCode;
use Modules\TitanPay\Services\QrPaymentService;

class PaymentQrController extends AccountBaseController
{
    public function __construct(
        private readonly QrPaymentService $qrService
    ) {
        parent::__construct();
        $this->pageTitle = 'TitanPay — Payment QR Codes';
    }

    /**
     * List all QR codes for a given invoice.
     */
    public function index(Request $request, int $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $qrCodes = PaymentQrCode::where('invoice_id', $invoice->id)
            ->latest()
            ->paginate(20);

        return view('titanpay::qr.index', compact('invoice', 'qrCodes'));
    }

    /**
     * Generate a new payment QR code for an invoice.
     * POST /account/titanpay/qr/{invoiceId}/generate
     */
    public function generate(Request $request, int $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $expiry  = '+' . (int) config('titanpay.qr.expiry_days', 7) . ' days';

        $qr = $this->qrService->generate($invoice, $expiry);

        return Reply::successWithData(
            'QR code generated successfully.',
            ['qr' => $qr, 'image_url' => $this->qrService->imageUrl($qr)]
        );
    }

    /**
     * Show a single QR code (inline HTML).
     * GET /account/titanpay/qr/{id}
     */
    public function show(int $id)
    {
        $qr = PaymentQrCode::findOrFail($id);

        return view('titanpay::qr.show', [
            'qr'       => $qr,
            'imageHtml'=> $this->qrService->imageHtml($qr),
        ]);
    }

    /**
     * Record a QR scan — called when the payment page is opened via a QR-encoded link.
     * The token belongs to a PaymentLink record whose ID is stored on the QR code
     * via the payment_url, but the cleanest approach is to look up the PaymentLink
     * by token and then find its associated QR code.
     *
     * Public endpoint — no auth required.
     */
    public function recordScan(string $token)
    {
        // Look up the payment link by token first (properly indexed)
        $link = \Modules\TitanPay\Models\PaymentLink::where('token', $token)->first();

        if ($link) {
            // Find the QR code that belongs to the same invoice and is still active
            $qr = PaymentQrCode::where('invoice_id', $link->invoice_id)
                ->where('status', 'active')
                ->latest()
                ->first();

            $qr?->recordScan();
        }

        // Continue to payment page
        return redirect('/pay/' . $token);
    }
}
