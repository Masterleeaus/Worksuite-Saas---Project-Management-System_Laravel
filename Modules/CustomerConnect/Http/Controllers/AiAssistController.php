<?php

namespace Modules\CustomerConnect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CustomerConnect\Entities\AiSuggestion;
use Modules\CustomerConnect\Entities\Thread;

class AiAssistController extends Controller
{
    private function tenant(): array
    {
        $uid = (int) auth()->id();
        return ['company_id' => $uid, 'user_id' => $uid];
    }

    private function scopedThreadOrFail(int $threadId): Thread
    {
        $t = $this->tenant();
        return Thread::where('id', $threadId)
            ->where('company_id', $t['company_id'])
            ->where('user_id', $t['user_id'])
            ->firstOrFail();
    }

    public function dismiss(Request $request, int $thread, int $suggestion)
    {
        $this->scopedThreadOrFail($thread);
        $t = $this->tenant();

        $s = AiSuggestion::where('id', $suggestion)
            ->where('thread_id', $thread)
            ->where('company_id', $t['company_id'])
            ->where('user_id', $t['user_id'])
            ->firstOrFail();

        $s->status = 'dismissed';
        $s->save();

        return back()->with('success', 'Suggestion dismissed.');
    }

    public function apply(Request $request, int $thread, int $suggestion)
    {
        $this->scopedThreadOrFail($thread);
        $t = $this->tenant();

        $s = AiSuggestion::where('id', $suggestion)
            ->where('thread_id', $thread)
            ->where('company_id', $t['company_id'])
            ->where('user_id', $t['user_id'])
            ->firstOrFail();

        $s->status = 'applied';
        $s->save();

        return back()->with('success', 'Suggestion marked as applied.');
    }
}
