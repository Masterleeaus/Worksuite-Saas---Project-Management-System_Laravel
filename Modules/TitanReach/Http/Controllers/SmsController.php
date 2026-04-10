<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Models\ReachContact;
use Modules\TitanReach\Services\TwilioSmsService;

class SmsController extends Controller
{
    public function __construct(protected TwilioSmsService $sms) {}

    public function index()
    {
        $companyId = auth()->user()?->company_id ?? null;
        $numbers = \Modules\TitanReach\Models\ReachSmsNumber::when($companyId, fn ($q) => $q->where('company_id', $companyId))->get();

        return view('titanreach::sms.index', compact('numbers'));
    }

    public function create()
    {
        return view('titanreach::sms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'account_sid'  => 'nullable|string|max:255',
        ]);
        $data['company_id'] = auth()->user()?->company_id;

        \Modules\TitanReach\Models\ReachSmsNumber::create($data);

        return redirect()->route('titanreach.sms.index')->with('success', 'SMS number added.');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'to'   => 'required|string',
            'body' => 'required|string',
            'from' => 'nullable|string',
        ]);

        $result = $this->sms->send($data['to'], $data['body'], $data['from'] ?? null);

        return back()->with('success', 'Message sent. SID: ' . ($result['sid'] ?? 'n/a'));
    }
}
