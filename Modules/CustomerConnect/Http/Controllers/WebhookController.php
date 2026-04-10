<?php

namespace Modules\CustomerConnect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CustomerConnect\Services\Webhooks\InboundMessageService;
use Modules\CustomerConnect\Services\Webhooks\WebhookSignatureVerifier;

/**
 * Stateles webhook endpoints for inbound messages.
 *
 * IMPORTANT: Tenancy is resolved from channel identities (To number / bot id).
 * These endpoints do NOT use account/auth middleware.
 */
class WebhookController extends Controller
{
    public function twilio(Request $request, InboundMessageService $service)
    {
$verifier = app(WebhookSignatureVerifier::class);
if (! $verifier->verifyTwilio($request)) {
    return response('Forbidden', 403);
}

$service->ingestTwilio($request);
return response('OK', 200);
    }

    public function vonage(Request $request, InboundMessageService $service)
    {
$verifier = app(WebhookSignatureVerifier::class);
if (! $verifier->verifyVonage($request)) {
    return response('Forbidden', 403);
}

$service->ingestVonage($request);
return response('OK', 200);
    }

    public function telegram(Request $request, InboundMessageService $service)
    {
$verifier = app(WebhookSignatureVerifier::class);
if (! $verifier->verifyTelegram($request)) {
    return response('Forbidden', 403);
}

$service->ingestTelegram($request);
return response()->json(['ok' => true]);
    }
}
