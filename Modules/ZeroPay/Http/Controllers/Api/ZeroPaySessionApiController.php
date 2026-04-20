<?php

namespace Modules\ZeroPay\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ZeroPay\Models\ZeroPayAttempt;
use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Services\ZeroPaySessionService;

class ZeroPaySessionApiController extends Controller
{
    public function __construct(private readonly ZeroPaySessionService $sessionService)
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'integer', 'exists:invoices,id'],
            'amount' => ['nullable', 'numeric', 'min:0.01'],
            'source_type' => ['nullable', 'string', 'max:50'],
            'source_reference' => ['nullable', 'string', 'max:191'],
        ]);

        $session = $this->sessionService->createSession($validated);

        return response()->json(['status' => 'success', 'data' => $session]);
    }

    public function show(int $id)
    {
        $session = ZeroPaySession::query()->with(['invoice', 'attempts', 'downloadTokens'])->findOrFail($id);

        return response()->json(['status' => 'success', 'data' => $session]);
    }

    public function resend(int $id)
    {
        $session = ZeroPaySession::query()->findOrFail($id);
        $followup = $this->sessionService->queueEmailFollowup($session);

        return response()->json(['status' => 'success', 'message' => 'Resend queued.', 'data' => $followup]);
    }

    public function markCash(int $id)
    {
        $session = ZeroPaySession::query()->findOrFail($id);

        $attempt = ZeroPayAttempt::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'method' => 'cash',
            'rail_type' => 'zero_fee',
            'status' => 'confirmed',
            'amount' => $session->amount,
            'confirmed_at' => now(),
            'payload' => ['source' => 'operator_mark_cash'],
        ]);

        $payment = $this->sessionService->confirmAttempt($attempt, ['remarks' => 'Cash payment confirmed by operator']);

        return response()->json(['status' => 'success', 'message' => 'Cash marked and posted.', 'payment_id' => $payment->id]);
    }

    public function bankConfirm(int $id)
    {
        $session = ZeroPaySession::query()->findOrFail($id);

        $attempt = ZeroPayAttempt::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'method' => 'bank_transfer',
            'rail_type' => 'zero_fee',
            'status' => 'confirmed',
            'amount' => $session->amount,
            'confirmed_at' => now(),
            'payload' => ['source' => 'operator_bank_confirm'],
        ]);

        $payment = $this->sessionService->confirmAttempt($attempt, ['remarks' => 'Bank transfer confirmed by operator']);

        return response()->json(['status' => 'success', 'message' => 'Bank transfer confirmed and posted.', 'payment_id' => $payment->id]);
    }

    public function voiceFollowup(int $id)
    {
        $session = ZeroPaySession::query()->findOrFail($id);
        $voiceRun = $this->sessionService->queueVoiceFollowup($session);

        return response()->json(['status' => 'success', 'data' => $voiceRun]);
    }
}
