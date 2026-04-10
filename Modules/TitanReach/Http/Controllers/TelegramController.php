<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Models\ReachTelegramBot;
use Modules\TitanReach\Services\TelegramService;

class TelegramController extends Controller
{
    public function __construct(protected TelegramService $telegram) {}

    public function index()
    {
        $companyId = auth()->user()?->company_id ?? null;
        $bots      = ReachTelegramBot::when($companyId, fn ($q) => $q->where('company_id', $companyId))->get();

        return view('titanreach::telegram.index', compact('bots'));
    }

    public function create()
    {
        return view('titanreach::telegram.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'bot_token'   => 'required|string',
            'webhook_url' => 'nullable|url',
        ]);

        $data['company_id'] = auth()->user()?->company_id;

        ReachTelegramBot::create($data);

        return redirect()->route('titanreach.telegram.index')->with('success', 'Telegram bot added.');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'chat_id' => 'required|string',
            'text'    => 'required|string',
        ]);

        $result = $this->telegram->sendMessage($data['chat_id'], $data['text']);

        return back()->with('success', 'Telegram message sent. Result: ' . ($result['ok'] ? 'OK' : 'Failed'));
    }
}
