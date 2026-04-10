<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CustomerConnect\Entities\Thread;
use Modules\CustomerConnect\Entities\ThreadLink;
use Modules\CustomerConnect\Entities\ThreadNote;

class ThreadContextController extends AccountBaseController
{
    protected function companyId(): ?int
    {
        try {
            if (function_exists('company') && company()) return (int) company()->id;
        } catch (\Throwable $e) {}
        try {
            if (function_exists('user') && user()) return (int) (user()->company_id ?? null);
        } catch (\Throwable $e) {}
        return null;
    }

    protected function assertTenant(Thread $thread): void
    {
        $companyId = $this->companyId();
        if ($companyId && (int)$thread->company_id !== (int)$companyId) {
            abort(404);
        }
    }

    public function addLink(Request $request, Thread $thread)
    {
        $this->assertTenant($thread);

        $data = $request->validate([
            'record_type' => 'required|string|max:50',
            'record_id' => 'nullable|integer|min:1',
            'label' => 'nullable|string|max:191',
            'url' => 'nullable|string|max:500',
        ]);

        // Cleaning-business-friendly link types (keep existing for compatibility)
        $allowedTypes = ['job','invoice','ticket','appointment','project','checklist','before_after','other'];
        if (!in_array($data['record_type'], $allowedTypes, true)) {
            return redirect()->back()->withErrors(['record_type' => 'Invalid record type.']);
        }

        ThreadLink::query()->updateOrCreate([
            'thread_id' => $thread->id,
            'record_type' => $data['record_type'],
            'record_id' => $data['record_id'] ?? null,
        ], [
            'company_id' => $thread->company_id,
            'user_id' => $thread->user_id ?? (function_exists('user') && user() ? user()->id : null),
            'label' => $data['label'] ?? null,
            'url' => $data['url'] ?? null,
            'meta' => [
                'added_by' => function_exists('user') && user() ? user()->id : null,
            ],
        ]);

        return redirect()->route('customerconnect.inbox.threads.show', $thread->id);
    }

    public function removeLink(Request $request, Thread $thread, ThreadLink $link)
    {
        $this->assertTenant($thread);
        if ((int)$link->thread_id !== (int)$thread->id) abort(404);
        if ((int)$link->company_id !== (int)$thread->company_id) abort(404);

        $link->delete();

        return redirect()->route('customerconnect.inbox.threads.show', $thread->id);
    }

    public function addNote(Request $request, Thread $thread)
    {
        $this->assertTenant($thread);

        $data = $request->validate([
            'note' => 'required|string',
        ]);

        ThreadNote::create([
            'company_id' => $thread->company_id,
            'user_id' => $thread->user_id ?? (function_exists('user') && user() ? user()->id : null),
            'thread_id' => $thread->id,
            'created_by' => function_exists('user') && user() ? user()->id : null,
            'body' => trim($data['note']),
            'meta' => [
                'visibility' => 'internal',
            ],
        ]);

        return redirect()->route('customerconnect.inbox.threads.show', $thread->id);
    }

    public function deleteNote(Request $request, Thread $thread, ThreadNote $note)
    {
        $this->assertTenant($thread);
        if ((int)$note->thread_id !== (int)$thread->id) abort(404);
        if ((int)$note->company_id !== (int)$thread->company_id) abort(404);

        $note->delete();

        return redirect()->route('customerconnect.inbox.threads.show', $thread->id);
    }
}
