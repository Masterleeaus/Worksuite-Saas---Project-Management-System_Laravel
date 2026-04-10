<?php

namespace Modules\Communication\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Modules\Communication\app\Models\Communication;
use Modules\Communication\app\Models\CommunicationTemplate;

class BulkSendController extends Controller
{
    /**
     * Show the bulk send form.
     */
    public function index(): View
    {
        $companyId = user()->company_id ?? null;

        $templates = CommunicationTemplate::when($companyId, fn ($q) => $q->forCompany($companyId))
            ->active()
            ->orderBy('name')
            ->get();

        return view('communication::bulk.index', [
            'templates' => $templates,
            'channels'  => Communication::channelLabels(),
        ]);
    }

    /**
     * Send a bulk message to multiple recipients.
     */
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type'        => 'required|in:email,sms,chat,push',
            'subject'     => 'nullable|string|max:512',
            'body'        => 'required|string',
            'template_id' => 'nullable|integer|exists:communication_templates,id',
            'recipients'  => 'required|string',
        ]);

        $companyId  = user()->company_id ?? null;
        $senderId   = Auth::id();

        // Parse recipients: comma-separated emails/phone numbers
        $addresses = array_filter(
            array_map('trim', explode(',', $validated['recipients'])),
            fn ($addr) => $addr !== ''
        );

        $queued = 0;
        foreach ($addresses as $address) {
            Communication::create([
                'company_id'   => $companyId,
                'type'         => $validated['type'],
                'from_user_id' => $senderId,
                'to_address'   => $address,
                'template_id'  => $validated['template_id'] ?? null,
                'subject'      => $validated['subject'] ?? null,
                'body'         => $validated['body'],
                'status'       => 'queued',
            ]);
            $queued++;
        }

        return redirect()->route('communications.bulk')
            ->with('success', "Queued {$queued} message(s) for delivery.");
    }
}
