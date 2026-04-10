<?php

namespace Modules\TitanReach\Http\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Services\TwilioSmsService;

class TwilioSmsWebhookController extends Controller
{
    public function __construct(protected TwilioSmsService $sms) {}

    public function inbound(Request $request)
    {
        $this->sms->receiveInbound($request->all());

        return response('<?xml version="1.0" encoding="UTF-8"?><Response></Response>', 200)
            ->header('Content-Type', 'text/xml');
    }
}
