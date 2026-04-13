<?php

namespace Modules\TitanTalk\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanTalk\Models\TitanTalkMessage;
use Modules\TitanTalk\Models\TitanTalkMessageReaction;

class ReactionController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request, TitanTalkMessage $message): JsonResponse
    {
        if ($message->company_id !== company()->id) {
            abort(403);
        }

        $request->validate(['emoji' => 'required|string|max:64']);

        TitanTalkMessageReaction::firstOrCreate([
            'message_id' => $message->id,
            'user_id'    => user()->id,
            'emoji'      => $request->emoji,
        ]);

        return response()->json([
            'status'         => 'success',
            'summary'        => $message->reactionSummary(),
            'user_reactions' => $message->reactions()
                ->where('user_id', user()->id)
                ->pluck('emoji')
                ->toArray(),
        ]);
    }

    public function destroy(TitanTalkMessage $message, string $emoji): JsonResponse
    {
        if ($message->company_id !== company()->id) {
            abort(403);
        }

        TitanTalkMessageReaction::where('message_id', $message->id)
            ->where('user_id', user()->id)
            ->where('emoji', $emoji)
            ->delete();

        return response()->json([
            'status'         => 'success',
            'summary'        => $message->reactionSummary(),
            'user_reactions' => $message->reactions()
                ->where('user_id', user()->id)
                ->pluck('emoji')
                ->toArray(),
        ]);
    }
}
