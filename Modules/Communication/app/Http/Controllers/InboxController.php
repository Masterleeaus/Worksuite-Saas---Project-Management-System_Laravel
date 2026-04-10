<?php

namespace Modules\Communication\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Modules\Communication\app\Models\Communication;

class InboxController extends Controller
{
    /**
     * Unified inbox — all channels for the authenticated user's company.
     */
    public function index(Request $request): View
    {
        $companyId = user()->company_id ?? null;

        $query = Communication::query()
            ->when($companyId, fn ($q) => $q->forCompany($companyId))
            ->orderByDesc('created_at');

        // Filter by channel
        if ($type = $request->input('type')) {
            $query->ofType($type);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Search in subject / body
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhere('to_address', 'like', "%{$search}%");
            });
        }

        $communications = $query->paginate(25)->withQueryString();

        return view('communication::inbox.index', [
            'communications' => $communications,
            'channels'       => Communication::channelLabels(),
            'statuses'       => Communication::statusLabels(),
            'filter'         => $request->only(['type', 'status', 'q']),
        ]);
    }

    /**
     * Show a single communication record.
     */
    public function show(int $id): View
    {
        $companyId     = user()->company_id ?? null;
        $communication = Communication::when($companyId, fn ($q) => $q->forCompany($companyId))
            ->findOrFail($id);

        // Mark as read if it was delivered
        if ($communication->status === 'delivered') {
            $communication->update(['status' => 'read', 'read_at' => now()]);
        }

        return view('communication::inbox.show', compact('communication'));
    }

    /**
     * Show the compose form.
     */
    public function compose(): View
    {
        $companyId = user()->company_id ?? null;
        $templates = \Modules\Communication\app\Models\CommunicationTemplate::when(
            $companyId,
            fn ($q) => $q->forCompany($companyId)
        )->active()->orderBy('name')->get();

        return view('communication::compose.index', [
            'templates' => $templates,
            'channels'  => Communication::channelLabels(),
        ]);
    }

    /**
     * Send a message (compose → send).
     */
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type'       => 'required|in:email,sms,chat,push',
            'to_address' => 'required|string|max:255',
            'subject'    => 'nullable|string|max:512',
            'body'       => 'required|string',
        ]);

        Communication::create(array_merge($validated, [
            'company_id'   => user()->company_id ?? null,
            'from_user_id' => Auth::id(),
            'status'       => 'queued',
        ]));

        return redirect()->route('communications.index')
            ->with('success', 'Message queued for delivery.');
    }

    /**
     * Communication history — all messages.
     */
    public function history(Request $request): View
    {
        $companyId = user()->company_id ?? null;

        $communications = Communication::when($companyId, fn ($q) => $q->forCompany($companyId))
            ->orderByDesc('created_at')
            ->paginate(50)
            ->withQueryString();

        return view('communication::history.index', compact('communications'));
    }

    /**
     * Communication history for a specific customer.
     */
    public function customerHistory(int $customerId): View
    {
        $companyId = user()->company_id ?? null;

        $communications = Communication::when($companyId, fn ($q) => $q->forCompany($companyId))
            ->where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('communication::history.customer', compact('communications', 'customerId'));
    }
}
