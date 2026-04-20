<?php

namespace Modules\ZeroPay\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ZeroPay\Models\ZeroPayDownloadToken;
use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Services\ZeroPaySessionService;

class ZeroPaySessionController extends Controller
{
    public function __construct(private readonly ZeroPaySessionService $sessionService)
    {
    }

    public function show(string $token)
    {
        $session = ZeroPaySession::query()
            ->with(['invoice.client', 'attempts' => fn ($q) => $q->latest(), 'downloadTokens'])
            ->where('public_token', $token)
            ->firstOrFail();

        $methods = array_merge(
            config('zeropay.rails.zero_fee', []),
            config('zeropay.rails.processing_fee', [])
        );

        return view('zeropay::session.show', compact('session', 'methods'));
    }

    public function selectMethod(Request $request, string $token)
    {
        $validated = $request->validate([
            'method' => ['required', 'string', 'max:60'],
        ]);

        $session = ZeroPaySession::query()->where('public_token', $token)->firstOrFail();
        $this->sessionService->selectMethod($session, $validated['method']);

        return back()->with('status', 'Payment method selected successfully.');
    }

    public function emailInvoice(string $token)
    {
        $session = ZeroPaySession::query()->where('public_token', $token)->firstOrFail();
        $this->sessionService->queueEmailFollowup($session, 'Invoice copy requested from ZeroPay session page.');

        return back()->with('status', 'Invoice email follow-up queued.');
    }

    public function downloadInvoice(string $token)
    {
        $downloadToken = ZeroPayDownloadToken::query()
            ->where('token', $token)
            ->where('type', 'invoice')
            ->firstOrFail();

        if ($downloadToken->expires_at && now()->greaterThan($downloadToken->expires_at)) {
            abort(410, 'Download token expired.');
        }

        $downloadToken->used_at = now();
        $downloadToken->save();

        return redirect()->route('front.invoice_download', md5((string) $downloadToken->invoice_id));
    }

    public function downloadReceipt(string $token)
    {
        $downloadToken = ZeroPayDownloadToken::query()
            ->where('token', $token)
            ->where('type', 'receipt')
            ->firstOrFail();

        if ($downloadToken->expires_at && now()->greaterThan($downloadToken->expires_at)) {
            abort(410, 'Download token expired.');
        }

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
