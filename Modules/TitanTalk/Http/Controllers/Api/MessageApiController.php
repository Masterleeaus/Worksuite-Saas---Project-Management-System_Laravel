<?php

namespace Modules\TitanTalk\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanTalk\Models\TitanTalkMessage;
use Modules\TitanTalk\Models\TitanTalkRoom;

class MessageApiController extends Controller
{
    public function index(TitanTalkRoom $room): JsonResponse
    {
        if ($room->company_id !== auth()->user()?->company_id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $messages = $room->messages()
            ->with(['author', 'files', 'reactions'])
            ->orderBy('created_at')
            ->paginate(50);

        return response()->json($messages);
    }

    public function store(Request $request, TitanTalkRoom $room): JsonResponse
    {
        if ($room->company_id !== auth()->user()?->company_id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $request->validate(['body' => 'required|string|max:10000']);

        $message = TitanTalkMessage::create([
            'company_id' => $room->company_id,
            'room_id'    => $room->id,
            'user_id'    => auth()->id(),
            'body'       => $request->body,
        ]);

        return response()->json(['status' => 'success', 'data' => $message->load('author')], 201);
    }
}
