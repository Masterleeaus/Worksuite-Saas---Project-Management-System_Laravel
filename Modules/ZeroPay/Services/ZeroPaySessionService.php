<?php

namespace Modules\ZeroPay\Services;

use App\Models\Invoice;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\ZeroPay\Models\ZeroPayAttempt;
use Modules\ZeroPay\Models\ZeroPayChannelLog;
use Modules\ZeroPay\Models\ZeroPayDownloadToken;
use Modules\ZeroPay\Models\ZeroPayFollowup;
use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Models\ZeroPayVoiceRun;

class ZeroPaySessionService
{
    public function __construct(private readonly ZeroPayPaymentPostingService $paymentPostingService)
    {
    }

    public function createSession(array $payload): ZeroPaySession
    {
        $invoice = Invoice::query()->findOrFail((int) $payload['invoice_id']);

        $session = ZeroPaySession::query()->create([
            'company_id' => (int) $invoice->company_id,
            'invoice_id' => (int) $invoice->id,
            'client_id' => (int) $invoice->client_id,
            'source_type' => $payload['source_type'] ?? 'invoice',
            'source_reference' => $payload['source_reference'] ?? null,
            'public_token' => Str::lower(Str::random(64)),
            'status' => 'sent',
            'selected_method' => null,
            'amount' => (float) ($payload['amount'] ?? $invoice->due_amount ?? $invoice->total),
            'currency_id' => (int) $invoice->currency_id,
            'expires_at' => now()->addDays(7),
            'created_by' => auth()->id(),
            'meta' => Arr::only($payload, ['booking_id', 'notes']),
        ]);

        $session->payment_link = route('zeropay.session.show', $session->public_token);
        $session->qr_payload = $session->payment_link;
        $session->save();

        $expiryMinutes = (int) config('zeropay.download_expiry_minutes', 1440);

        foreach (['invoice', 'receipt'] as $type) {
            ZeroPayDownloadToken::query()->create([
                'company_id' => $session->company_id,
                'session_id' => $session->id,
                'invoice_id' => $session->invoice_id,
                'type' => $type,
                'token' => Str::lower(Str::random(64)),
                'expires_at' => now()->addMinutes($expiryMinutes),
            ]);
        }

        return $session->fresh(['invoice', 'attempts', 'downloadTokens']);
    }

    public function selectMethod(ZeroPaySession $session, string $method): ZeroPayAttempt
    {
        $zeroFee = config('zeropay.rails.zero_fee', []);
        $railType = in_array($method, $zeroFee, true) ? 'zero_fee' : 'processing_fee';

        $attempt = ZeroPayAttempt::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'method' => $method,
            'rail_type' => $railType,
            'status' => in_array($method, ['cash', 'payid', 'bank_transfer'], true) ? 'awaiting_confirmation' : 'pending_gateway',
            'amount' => (float) $session->amount,
            'fee_amount' => $railType === 'processing_fee' ? (float) ($session->amount * 0.02) : 0,
            'payload' => ['selected_at' => now()->toIso8601String()],
        ]);

        $session->selected_method = $method;
        $session->status = $attempt->status;
        $session->save();

        ZeroPayChannelLog::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'channel' => 'web',
            'status' => 'logged',
            'subject' => 'Method selected',
            'body' => 'Customer selected payment method: ' . $method,
            'sent_at' => now(),
        ]);

        return $attempt;
    }

    public function queueEmailFollowup(ZeroPaySession $session, string $message = 'Invoice reminder sent by ZeroPay'): ZeroPayFollowup
    {
        $followup = ZeroPayFollowup::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'channel' => 'email',
            'status' => 'queued',
            'scheduled_at' => now(),
            'message' => $message,
            'ai_suggested' => true,
        ]);

        ZeroPayChannelLog::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'followup_id' => $followup->id,
            'channel' => 'email',
            'status' => 'queued',
            'subject' => 'ZeroPay follow-up',
            'body' => $message,
        ]);

        return $followup;
    }

    public function queueVoiceFollowup(ZeroPaySession $session): ZeroPayVoiceRun
    {
        $followup = ZeroPayFollowup::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'channel' => 'voice',
            'status' => 'queued',
            'scheduled_at' => now(),
            'message' => 'Voice follow-up queued by operator.',
        ]);

        return ZeroPayVoiceRun::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'followup_id' => $followup->id,
            'provider' => (string) config('zeropay.voice.provider', 'twilio'),
            'status' => 'queued',
            'meta' => ['queued_by' => auth()->id()],
        ]);
    }

    public function confirmAttempt(ZeroPayAttempt $attempt, array $payload = [])
    {
        return $this->paymentPostingService->postConfirmedAttempt($attempt, $payload);
    }
}
