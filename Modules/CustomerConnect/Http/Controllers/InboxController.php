<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CustomerConnect\Entities\SavedFilter;
use Modules\CustomerConnect\Entities\Thread;

class InboxController extends AccountBaseController
{
    public function index(Request $request)
    {
        $this->pageTitle = 'Customer Connect - Inbox';

        // ISSUE D FIX: always scope to current tenant's company
        $companyId = (int) company()->id;

        $q        = trim((string) $request->get('q', ''));
        $status   = $request->get('status');   // open/closed/pending
        $channel  = $request->get('channel');  // sms/whatsapp/telegram/email
        $tagId    = $request->get('tag_id');
        $assignee = $request->get('assigned_to');

        $threads = Thread::query()
            ->with('contact')
            ->where('company_id', $companyId)  // ISSUE D FIX: tenant scoped
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('subject', 'like', "%{$q}%")
                       ->orWhere('last_message_preview', 'like', "%{$q}%")
                       ->orWhereHas('contact', fn ($c) =>
                           $c->where('display_name', 'like', "%{$q}%")
                             ->orWhere('email', 'like', "%{$q}%")
                             ->orWhere('phone_e164', 'like', "%{$q}%")
                       );
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($channel, fn ($query) => $query->where('channel', $channel))
            ->when($assignee, fn ($query) => $query->where('assigned_to_user_id', $assignee))
            ->when($tagId, function ($query) use ($tagId) {
                $query->whereExists(function ($sub) use ($tagId) {
                    $sub->selectRaw(1)
                        ->from('customerconnect_thread_tags')
                        ->whereColumn('customerconnect_thread_tags.thread_id', 'customerconnect_threads.id')
                        ->where('customerconnect_thread_tags.tag_id', $tagId);
                });
            })
            ->orderByDesc('last_message_at')
            ->paginate(25)
            ->withQueryString();

        // ISSUE E FIX: scope saved filters by both user and company
        $savedFilters = SavedFilter::query()
            ->where('user_id', user()->id)
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get();

        return view('customerconnect::inbox.index', compact('threads', 'q', 'savedFilters'));
    }

    public function create()
    {
        return view('customerconnect::inbox.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|integer',
            'channel'    => 'required|string',
            'message'    => 'required|string',
            'subject'    => 'nullable|string|max:191',
        ]);

        $companyId = (int) company()->id;

        $thread = Thread::query()->firstOrCreate([
            'company_id' => $companyId,
            'contact_id' => (int) $request->contact_id,
            'channel'    => $request->channel,
        ], [
            'status'               => 'open',
            'last_message_at'      => now(),
            'last_message_preview' => '',
        ]);

        session()->put('cc_initial_message', $request->message);

        return redirect()->route('customerconnect.inbox.threads.show', $thread->id);
    }
}
