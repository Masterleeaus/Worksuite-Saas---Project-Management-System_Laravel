<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\CustomerConnect\Services\Callbacks\StatusCallbackService;

/**
 * Delivery status callbacks (no inbox UI, no threads logic).
 * These endpoints should be hit by providers (Twilio/Vonage) to update message/delivery states.
 *
 * SECURITY: Prefer provider signature verification at the web server / gateway layer.
 * This controller performs best-effort validation if configured in customerconnect.php.
 */
class ProviderCallbackController extends Controller
{
    public function twilio(Request $request, StatusCallbackService $svc)
    {
        return $svc->handleTwilioStatusCallback($request);
    }

    public function vonage(Request $request, StatusCallbackService $svc)
    {
        return $svc->handleVonageStatusCallback($request);
    }
}
