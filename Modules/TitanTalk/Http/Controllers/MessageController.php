<?php

namespace Modules\TitanTalk\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanTalk\Events\TitanTalkMessageSent;
use Modules\TitanTalk\Models\TitanTalkMessage;
use Modules\TitanTalk\Models\TitanTalkMessageSave;
use Modules\TitanTalk\Models\TitanTalkRoom;
use Modules\TitanTalk\Models\TitanTalkRoomMember;
use Modules\TitanTalk\Models\TitanTalkRoomPin;
use Modules\TitanTalk\Notifications\TitanTalkNewMessage;

class MessageController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(TitanTalkRoom $room): JsonResponse
    {
        $this->authorizeRoomAccess($room);

        $messages = $room->messages()
            ->with(['author', 'files', 'reactions', 'pin'])
            ->orderBy('created_at')
            ->paginate(50);

        // Mark as read
        TitanTalkRoomMember::where('room_id', $room->id)
            ->where('user_id', user()->id)
            ->update(['last_read_at' => now()]);

        return response()->json($messages);
    }

    public function store(Request $request, TitanTalkRoom $room): JsonResponse
    {
        $this->authorizeRoomAccess($room);

        $request->validate([
            'body'  => 'required|string|max:10000',
            'files' => 'nullable|array',
        ]);

        $message = TitanTalkMessage::create([
            'company_id' => company()->id,
            'room_id'    => $room->id,
            'user_id'    => user()->id,
            'body'       => $request->body,
        ]);

        // Handle file attachments
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('titan-talk/' . $room->id, 'public');
                $message->files()->create([
                    'filename'          => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'mime_type'         => $file->getMimeType(),
                    'file_size'         => $file->getSize(),
                    'disk'              => 'public',
                ]);
            }
        }

        $message->load(['author', 'files', 'reactions']);

        // Broadcast
        event(new TitanTalkMessageSent($message));

        // Notify members (skip muted and sender)
        $members = TitanTalkRoomMember::where('room_id', $room->id)
            ->where('user_id', '!=', user()->id)
            ->where('is_muted', false)
            ->with('user')
            ->get();

        foreach ($members as $member) {
            if ($member->user) {
                $member->user->notify(new TitanTalkNewMessage($message));
            }
        }

        return response()->json(['status' => 'success', 'message' => $message], 201);
    }

    public function update(Request $request, TitanTalkMessage $message): JsonResponse
    {
        if ($message->user_id !== user()->id) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|max:10000']);

        $message->update(['body' => $request->body, 'is_edited' => true]);

        return response()->json(['status' => 'success', 'message' => $message]);
    }

    public function destroy(TitanTalkMessage $message): JsonResponse
    {
        if ($message->user_id !== user()->id) {
            $role = $message->room?->getMemberRole(user()->id);
            if (!in_array($role, ['owner', 'admin'])) {
                abort(403);
            }
        }

        $message->delete();

        return response()->json(['status' => 'success']);
    }

    public function pin(TitanTalkMessage $message): JsonResponse
    {
        $room = $message->room;
        $role = $room?->getMemberRole(user()->id);

        if (!in_array($role, ['owner', 'admin'])) {
            abort(403);
        }

        TitanTalkRoomPin::firstOrCreate([
            'room_id'    => $message->room_id,
            'message_id' => $message->id,
        ], ['pinned_by' => user()->id]);

        return response()->json(['status' => 'success']);
    }

    public function unpin(TitanTalkMessage $message): JsonResponse
    {
        $room = $message->room;
        $role = $room?->getMemberRole(user()->id);

        if (!in_array($role, ['owner', 'admin'])) {
            abort(403);
        }

        TitanTalkRoomPin::where('room_id', $message->room_id)
            ->where('message_id', $message->id)
            ->delete();

        return response()->json(['status' => 'success']);
    }

    public function save(TitanTalkMessage $message): JsonResponse
    {
        TitanTalkMessageSave::firstOrCreate([
            'user_id'    => user()->id,
            'message_id' => $message->id,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function unsave(TitanTalkMessage $message): JsonResponse
    {
        TitanTalkMessageSave::where('user_id', user()->id)
            ->where('message_id', $message->id)
            ->delete();

        return response()->json(['status' => 'success']);
    }

    public function pinned(TitanTalkRoom $room): JsonResponse
    {
        $this->authorizeRoomAccess($room);

        $pins = $room->pins()->with('message.author')->get();

        return response()->json(['pins' => $pins]);
    }

    public function saved(): JsonResponse
    {
        $saved = TitanTalkMessageSave::where('user_id', user()->id)
            ->with(['message.author', 'message.room'])
            ->latest()
            ->paginate(20);

        return response()->json($saved);
    }

    private function authorizeRoomAccess(TitanTalkRoom $room): void
    {
        if ($room->company_id !== company()->id) {
            abort(403);
        }

        if (in_array($room->type, ['private', 'dm']) && !$room->isMember(user()->id)) {
            abort(403);
        }
    }
}
