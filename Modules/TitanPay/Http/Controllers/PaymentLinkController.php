<?php

namespace Modules\TitanPay\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Modules\TitanPay\Models\PaymentLink;
use Modules\TitanPay\Services\PaymentDispatcher;
use Modules\TitanPay\Services\PaymentLinkService;

class PaymentLinkController extends AccountBaseController
{
    public function __construct(
        private readonly PaymentLinkService $linkService,
        private readonly PaymentDispatcher  $dispatcher
    ) {
        parent::__construct();
        $this->pageTitle = 'TitanPay — Payment Links';
    }

    /**
     * Generate (or return an existing active) payment link for an invoice.
     * POST /account/titanpay/links/{invoiceId}/generate
     */
    public function generate(Request $request, int $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $expiry  = '+' . (int) config('titanpay.payment_link.expiry_days', 30) . ' days';
        $gateway = $request->input('gateway', 'any');

        $link = $this->linkService->generate($invoice, $expiry, $gateway);

        return Reply::successWithData(
            'Payment link generated.',
            ['url' => $this->linkService->url($link), 'link' => $link]
        );
    }

    /**
     * Public payment page — no login required.
     * GET /pay/{token}
     */
    public function show(string $token)
    {
        $link = $this->linkService->findValid($token);

        if (!$link) {
            abort(404, 'This payment link is invalid or has expired.');
        }

        $invoice = $link->invoice()->with('client', 'currency', 'items')->firstOrFail();

        return view('titanpay::payment.pay', compact('link', 'invoice'));
    }

    /**
     * Process payment via selected gateway on the public payment page.
     * POST /pay/{token}/process
     */
    public function process(Request $request, string $token)
    {
        $link = $this->linkService->findValid($token);

        if (!$link) {
            abort(404, 'This payment link is invalid or has expired.');
        }

        $gateway = $request->input('gateway', $link->gateway === 'any' ? 'cryptomus' : $link->gateway);
        $invoice = $link->invoice;

        try {
            $result = $this->dispatcher->dispatch($invoice, $gateway);

            return redirect()->away($result['redirect_url']);
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * List payment links for an invoice (admin view).
     * GET /account/titanpay/links/{invoiceId}
     */
    public function index(int $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $links = PaymentLink::where('invoice_id', $invoice->id)
            ->latest()
            ->paginate(20);

        return view('titanpay::payment.links', compact('invoice', 'links'));
    }
}
