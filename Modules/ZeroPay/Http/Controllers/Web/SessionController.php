<?php

namespace Modules\ZeroPay\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Modules\ZeroPay\Http\Requests\SelectPaymentMethodRequest;
use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Services\DownloadTokenService;
use Modules\ZeroPay\Services\PaymentSessionService;
use Modules\ZeroPay\ViewModels\SessionPageViewModel;

class SessionController extends Controller
{
    public function __construct(
        private readonly PaymentSessionService $sessions,
        private readonly DownloadTokenService $downloadTokens,
    ) {
    }

    public function show(string $token)
    {
        $session = ZeroPaySession::query()
            ->with(['invoice.client', 'attempts' => fn ($q) => $q->latest(), 'downloadTokens'])
            ->where('public_token', $token)
            ->firstOrFail();

        $vm = new SessionPageViewModel($session);

        return view('zeropay::session.show', $vm->toArray());
    }

    public function selectMethod(SelectPaymentMethodRequest $request, string $token)
    {
        $session = ZeroPaySession::query()->where('public_token', $token)->firstOrFail();
        $this->sessions->selectMethod($session, (string) $request->validated('method'));

        return back()->with('status', 'Payment method selected successfully.');
    }

    public function emailInvoice(string $token)
    {
        $session = ZeroPaySession::query()->where('public_token', $token)->firstOrFail();
        $this->sessions->resend($session);

        return back()->with('status', 'Invoice email follow-up queued.');
    }

    public function downloadInvoice(string $token)
    {
        $downloadToken = $this->downloadTokens->validateToken($token, 'invoice');
        $downloadToken->used_at = now();
        $downloadToken->save();

        return redirect()->route('front.invoice_download', md5((string) $downloadToken->invoice_id));
    }

    public function downloadReceipt(string $token)
    {
        $downloadToken = $this->downloadTokens->validateToken($token, 'receipt');
        $downloadToken->used_at = now();
        $downloadToken->save();

        $session = $downloadToken->session;
        $attempt = $session?->attempts()->latest()->first();

        $receipt = [
            'ZeroPay Session' => $session?->public_token,
            'Invoice ID' => $downloadToken->invoice_id,
            'Amount' => number_format((float) ($session?->amount ?? 0), 2),
            'Method' => $attempt?->method ?? 'N/A',
            'Status' => $session?->status ?? 'N/A',
            'Generated At' => now()->toDateTimeString(),
        ];

        $content = collect($receipt)->map(fn ($value, $key) => $key . ': ' . $value)->implode(PHP_EOL) . PHP_EOL;

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="zeropay-receipt-' . ($downloadToken->invoice_id ?? 'unknown') . '.txt"',
        ]);
    }
}
