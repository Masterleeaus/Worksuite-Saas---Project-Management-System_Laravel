<?php

namespace Modules\TitanReach\Http\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Services\TelegramService;

class TelegramWebhookController extends Controller
{
    public function __construct(protected TelegramService $telegram) {}

    public function inbound(Request $request)
    {
        $update = $request->all();

        if (!empty($update)) {
            $this->telegram->receiveInbound($update);
        }

        return response()->json(['ok' => true]);
    }
}
