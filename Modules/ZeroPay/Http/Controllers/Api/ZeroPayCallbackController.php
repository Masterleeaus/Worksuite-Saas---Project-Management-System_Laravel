<?php

namespace Modules\ZeroPay\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ZeroPay\Models\ZeroPayAttempt;
use Modules\ZeroPay\Services\ZeroPaySessionService;

class ZeroPayCallbackController extends Controller
{
    public function __construct(private readonly ZeroPaySessionService $sessionService)
    {
    }

    public function stripe(Request $request)
    {
        return $this->handleGatewayCallback($request, 'stripe');
    }

    public function paypal(Request $request)
    {
        return $this->handleGatewayCallback($request, 'paypal');
    }

    public function cryptomus(Request $request)
    {
        return $this->handleGatewayCallback($request, 'cryptomus');
    }

    private function handleGatewayCallback(Request $request, string $gateway)
    {
        $attemptId = (int) ($request->input('attempt_id') ?? $request->input('metadata.attempt_id') ?? 0);

        if ($attemptId <= 0) {
            return response()->json(['status' => 'ignored', 'message' => 'No attempt_id provided.'], 202);
        }

        $attempt = ZeroPayAttempt::query()->findOrFail($attemptId);

        $status = (string) ($request->input('status') ?? $request->input('event') ?? 'pending');
        $normalized = strtolower($status);
        $isSuccess = str_contains($normalized, 'success') || str_contains($normalized, 'paid') || str_contains($normalized, 'confirm');

        if ($isSuccess) {
            $payment = $this->sessionService->confirmAttempt($attempt, [
                'transaction_id' => (string) ($request->input('transaction_id') ?? $request->input('id') ?? null),
                'remarks' => ucfirst($gateway) . ' callback confirmation',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => ucfirst($gateway) . ' callback confirmed and posted.',
                'payment_id' => $payment->id,
            ]);
        }

        $attempt->status = 'failed';
        $attempt->payload = array_merge((array) $attempt->payload, ['callback' => $request->all(), 'gateway' => $gateway]);
        $attempt->save();

        return response()->json(['status' => 'received', 'message' => ucfirst($gateway) . ' callback logged as non-success.']);
    }
}
