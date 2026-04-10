<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CustomerConnect\Entities\Message;
use Modules\CustomerConnect\Entities\Thread;
use Modules\CustomerConnect\Entities\ThreadRead;
use Modules\CustomerConnect\Services\Inbox\AssigneeResolver;
use Modules\CustomerConnect\Jobs\SendThreadMessage;

class ThreadController extends AccountBaseController
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

    public function show(Request $request, Thread $thread)
    {
        $companyId = $this->companyId();
        if ($companyId && (int)$thread->company_id !== (int)$companyId) {
            abort(404);
        }

        $thread->load(['contact', 'messages' => function ($q) {
            $q->orderBy('id', 'asc');
        }]);

        // Mark as read for current user (unread is derived from last_message_at > last_read_at)
        try {
            if (function_exists('user') && user()) {
                ThreadRead::query()->updateOrCreate([
                    'thread_id' => $thread->id,
                    'user_id' => user()->id,
                ], [
                    'company_id' => $thread->company_id,
                    'last_read_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Never break UI
        }

        // If create() redirected with a first message, send it now
        $initial = session()->pull('cc_initial_message');
        if ($initial) {
            $this->queueOutboundMessage($thread, $initial, $request->string('subject')->toString());
            return redirect()->route('customerconnect.inbox.threads.show', $thread->id);
        }

                $assignees = app(AssigneeResolver::class)->listAssignees($thread->company_id);

        return view('customerconnect::inbox.thread', compact('thread','assignees'));
    }

    public function send(Request $request, Thread $thread)
    {
        $companyId = $this->companyId();
        if ($companyId && (int)$thread->company_id !== (int)$companyId) abort(404);

        $data = $request->validate([
            'message' => 'required|string',
            'subject' => 'nullable|string|max:191',
        ]);

        $this->queueOutboundMessage($thread, $data['message'], $data['subject'] ?? null);

        return redirect()->route('customerconnect.inbox.threads.show', $thread->id);
    }

    public function assign(Request $request, Thread $thread)
    {
        $companyId = $this->companyId();
        if ($companyId && (int)$thread->company_id !== (int)$companyId) abort(404);

        $data = $request->validate([
            'assigned_to' => 'nullable|integer',
        ]);

        $thread->assigned_to = $data['assigned_to'] ?? null;
        $thread->save();

        return redirect()->route('customerconnect.inbox.threads.show', $thread->id);
    }

    public function close(Request $request, Thread $thread)
    {
        $companyId = $this->companyId();
        if ($companyId && (int)$thread->company_id !== (int)$companyId) abort(404);

        $thread->status = $thread->status === 'closed' ? 'open' : 'closed';
        $thread->save();

        return redirect()->route('customerconnect.inbox.threads.show', $thread->id);
    }

    protected function queueOutboundMessage(Thread $thread, string $body, ?string $subject = null): void
    {
        DB::transaction(function () use ($thread, $body, $subject) {
            $message = Message::create([
                'company_id' => $thread->company_id,
                'thread_id' => $thread->id,
                'direction' => 'outbound',
                'sender_user_id' => function_exists('user') && user() ? user()->id : null,
                'body_text' => $body,
                'status' => 'queued',
                'provider' => null,
                'provider_message_id' => null,
                'meta' => [
                    'subject' => $subject,
                ],
            ]);

            $thread->last_message_at = now();
            $thread->last_message_preview = mb_substr(trim($body), 0, 180);
            $thread->save();

            dispatch(new SendThreadMessage($message->id));
        });
    }
}
