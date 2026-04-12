<?php

namespace Modules\TitanPay\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;
use Modules\TitanPay\Models\PaymentQrCode;

/**
 * Generates QR codes for invoice payment links.
 * Delegates actual QR rendering to the QRCode module when available,
 * and falls back to a raw data-URI via endroid/qr-code directly.
 */
class QrPaymentService
{
    public function __construct(
        private readonly PaymentLinkService $linkService
    ) {}

    /**
     * Create (or reuse) a PaymentQrCode for an invoice.
     */
    public function generate(Invoice $invoice, string $expiry = '+7 days'): PaymentQrCode
    {
        // Reuse an active, unexpired QR for this invoice
        $existing = PaymentQrCode::query()
            ->where('invoice_id', $invoice->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($existing) {
            return $existing;
        }

        // Generate a payment link first
        $link       = $this->linkService->generate($invoice, $expiry);
        $paymentUrl = $this->linkService->url($link);

        // Build the QR image
        $qrImagePath = $this->renderQr($paymentUrl, $invoice->id);

        return PaymentQrCode::create([
            'company_id'    => $invoice->company_id,
            'invoice_id'    => $invoice->id,
            'payment_url'   => $paymentUrl,
            'qr_image_path' => $qrImagePath,
            'expires_at'    => now()->modify($expiry),
            'status'        => 'active',
        ]);
    }

    /**
     * Return the public URL for a QR code image.
     */
    public function imageUrl(PaymentQrCode $qr): string
    {
        if ($qr->qr_image_path) {
            return Storage::url($qr->qr_image_path);
        }

        return '';
    }

    /**
     * Return an inline HTML <img> tag for the QR code.
     */
    public function imageHtml(PaymentQrCode $qr): string
    {
        if ($qr->qr_image_path) {
            return '<img src="' . e($this->imageUrl($qr)) . '" alt="Payment QR Code" />';
        }

        return $this->renderQrHtml($qr->payment_url);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Render the QR code and store it; returns the storage path.
     */
    private function renderQr(string $paymentUrl, int $invoiceId): ?string
    {
        // Prefer the QRCode module helper when available
        if (class_exists(\Modules\QRCode\Support\QrCode::class)) {
            try {
                $builder = \Modules\QRCode\Support\QrCode::generate()
                    ->data($paymentUrl)
                    ->png();

                $storagePath = 'titanpay/qr/' . $invoiceId . '_' . time() . '.png';
                Storage::put($storagePath, $builder->build()->getString());

                return $storagePath;
            } catch (\Throwable) {
                // Fall through to inline render
            }
        }

        // Fallback: rely on endroid/qr-code directly (already a composer dependency via QRCode module)
        if (class_exists(\Endroid\QrCode\Builder\Builder::class)) {
            try {
                $result = \Endroid\QrCode\Builder\Builder::create()
                    ->data($paymentUrl)
                    ->writer(new \Endroid\QrCode\Writer\PngWriter())
                    ->size(200)
                    ->margin(0)
                    ->build();

                $storagePath = 'titanpay/qr/' . $invoiceId . '_' . time() . '.png';
                Storage::put($storagePath, $result->getString());

                return $storagePath;
            } catch (\Throwable) {
                // Could not render – qr_image_path will remain null
            }
        }

        return null;
    }

    /**
     * Render a QR code as an inline HTML img (data URI) without file storage.
     */
    private function renderQrHtml(string $paymentUrl): string
    {
        if (class_exists(\Modules\QRCode\Support\QrCode::class)) {
            try {
                $builder = \Modules\QRCode\Support\QrCode::generate()
                    ->data($paymentUrl)
                    ->png();

                return $builder->html();
            } catch (\Throwable) {
            }
        }

        return '';
    }
}
