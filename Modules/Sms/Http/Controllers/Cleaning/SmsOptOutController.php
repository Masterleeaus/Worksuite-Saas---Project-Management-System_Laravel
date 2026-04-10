<?php

namespace Modules\Sms\Http\Controllers\Cleaning;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\Sms\Entities\SmsOptOut;

/**
 * Admin controller for managing SMS opt-outs (STOP replies).
 */
class SmsOptOutController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'SMS Opt-Out Management';
        $this->activeSettingMenu = 'sms_setting';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('admin', user_roles()));
            return $next($request);
        });
    }

    public function index()
    {
        $optOuts = SmsOptOut::where('company_id', company()->id)
            ->with('user')
            ->orderByDesc('opted_out_at')
            ->paginate(50);

        return view('sms::cleaning.opt-outs', compact('optOuts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:30',
        ]);

        SmsOptOut::firstOrCreate([
            'company_id'   => company()->id,
            'phone_number' => $request->input('phone_number'),
        ], [
            'opted_out_at' => now(),
        ]);

        return back()->with('success', 'Phone number added to opt-out list.');
    }

    public function destroy(int $id)
    {
        SmsOptOut::where('company_id', company()->id)
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Opt-out removed.');
    }

    /**
     * Twilio webhook: handle inbound STOP / START / HELP replies.
     */
    public function twilioWebhook(Request $request)
    {
        $body = strtoupper(trim($request->input('Body', '')));
        $from = $request->input('From', '');

        if ($body === 'STOP') {
            // Find company by the "To" number (Twilio sends to your number)
            SmsOptOut::firstOrCreate([
                'phone_number' => $from,
                'company_id'   => null, // global opt-out when company unknown
            ], [
                'opted_out_at' => now(),
            ]);
        } elseif ($body === 'START' || $body === 'UNSTOP') {
            SmsOptOut::where('phone_number', $from)->delete();
        }

        // Twilio expects a TwiML response
        return response('<Response></Response>', 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Per-client channel preference update (AJAX).
     */
    public function updateChannelPreference(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'channel' => 'required|in:sms,whatsapp',
        ]);

        \Modules\Sms\Entities\SmsChannelPreference::updateOrCreate(
            [
                'company_id' => company()->id,
                'user_id'    => $request->integer('user_id'),
            ],
            ['channel' => $request->input('channel')]
        );

        return response()->json(['ok' => true]);
    }
}
