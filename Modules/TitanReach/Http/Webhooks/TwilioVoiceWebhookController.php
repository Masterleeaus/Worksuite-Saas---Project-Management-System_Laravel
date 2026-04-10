<?php

namespace Modules\TitanReach\Http\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Services\TwilioVoiceService;

class TwilioVoiceWebhookController extends Controller
{
    public function __construct(protected TwilioVoiceService $voice) {}

    public function inbound(Request $request)
    {
        $this->voice->receiveInbound($request->all());

        $twiml = '<?xml version="1.0" encoding="UTF-8"?><Response><Say>Thank you for calling. Please hold.</Say></Response>';

        return response($twiml, 200)->header('Content-Type', 'text/xml');
    }

    public function status(Request $request)
    {
        // Status callbacks – no TwiML response needed.
        return response()->json(['ok' => true]);
    }

    public function twiml(Request $request)
    {
        $script  = $request->input('script', 'Hello, this is an automated message.');
        $twiml   = $this->voice->generateTwiml($script);

        return response($twiml, 200)->header('Content-Type', 'text/xml');
    }
}
