<?php

namespace Modules\ZeroPay\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\ZeroPay\Http\Requests\CreatePaymentSessionRequest;
use Modules\ZeroPay\Http\Resources\SessionResource;
use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Services\PaymentSessionService;

class ZeroPaySessionApiController extends Controller
{
    public function __construct(private readonly PaymentSessionService $sessions)
    {
    }

    public function store(CreatePaymentSessionRequest $request)
    {
        $session = $this->sessions->create($request->validated());

        return response()->json(['status' => 'success', 'data' => new SessionResource($session)]);
    }

    public function show(int $id)
    {
        $session = ZeroPaySession::query()->with(['invoice', 'attempts', 'downloadTokens'])->findOrFail($id);

        return response()->json(['status' => 'success', 'data' => new SessionResource($session)]);
    }

    public function resend(int $id)
    {
        $session = ZeroPaySession::query()->findOrFail($id);
        $followup = $this->sessions->resend($session);

        return response()->json(['status' => 'success', 'message' => 'Resend queued.', 'data' => $followup]);
    }

    public function markCash(int $id)
    {
        $session = ZeroPaySession::query()->findOrFail($id);
        $payment = $this->sessions->markCash($session);

        return response()->json(['status' => 'success', 'message' => 'Cash marked and posted.', 'payment_id' => $payment->id]);
    }

    public function bankConfirm(int $id)
    {
        $session = ZeroPaySession::query()->findOrFail($id);
        $payment = $this->sessions->confirmBank($session);

        return response()->json(['status' => 'success', 'message' => 'Bank transfer confirmed and posted.', 'payment_id' => $payment->id]);
    }

    public function voiceFollowup(int $id)
    {
        $session = ZeroPaySession::query()->findOrFail($id);
        $voiceRun = $this->sessions->queueVoice($session);

        return response()->json(['status' => 'success', 'data' => $voiceRun]);
    }
}
