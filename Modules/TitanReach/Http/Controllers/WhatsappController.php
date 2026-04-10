<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Models\ReachWhatsappChannel;
use Modules\TitanReach\Services\TwilioWhatsappService;

class WhatsappController extends Controller
{
    public function __construct(protected TwilioWhatsappService $whatsapp) {}

    public function index()
    {
        $companyId = auth()->user()?->company_id ?? null;
        $channels  = ReachWhatsappChannel::when($companyId, fn ($q) => $q->where('company_id', $companyId))->get();

        return view('titanreach::whatsapp.index', compact('channels'));
    }

    public function create()
    {
        return view('titanreach::whatsapp.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'account_sid' => 'required|string',
            'auth_token'  => 'required|string',
            'from_number' => 'required|string',
        ]);

        $data['company_id'] = auth()->user()?->company_id;

        ReachWhatsappChannel::create($data);

        return redirect()->route('titanreach.whatsapp.index')->with('success', 'WhatsApp channel added.');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'to'        => 'required|string',
            'body'      => 'required|string',
            'media_url' => 'nullable|url',
        ]);

        $result = $this->whatsapp->send($data['to'], $data['body'], $data['media_url'] ?? null);

        return back()->with('success', 'WhatsApp message sent. SID: ' . ($result['sid'] ?? 'n/a'));
    }
}
