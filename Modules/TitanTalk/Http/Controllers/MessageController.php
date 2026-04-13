<?php

namespace Modules\TitanTalk\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanTalk\Events\TitanTalkMentionEvent;
use Modules\TitanTalk\Events\TitanTalkMessageSent;
use Modules\TitanTalk\Models\TitanTalkMessage;
use Modules\TitanTalk\Models\TitanTalkMessageSave;
use Modules\TitanTalk\Models\TitanTalkRoom;
use Modules\TitanTalk\Models\TitanTalkRoomMember;
use Modules\TitanTalk\Models\TitanTalkRoomPin;
use Modules\TitanTalk\Notifications\TitanTalkNewMessage;
use Modules\TitanTalk\Services\TitanTalkService;

class MessageController extends AccountBaseController
{
    public function __construct(private readonly TitanTalkService $ttService)
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
            'body'    => 'required|string|max:10000',
            'files'   => 'nullable|array',
            'files.*' => 'file|max:10240',
        ]);

        $message = TitanTalkMessage::create([
            'company_id' => company()->id,
            'room_id'    => $room->id,
            'user_id'    => user()->id,
            'body'       => $request->body,
        ]);

        // File attachments — uses Worksuite Files::uploadLocalOrS3 via TitanTalkService
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $this->ttService->attachFile($message, $file);
            }
        }

        $message->load(['author', 'files', 'reactions']);

        // Broadcast to room channel (reuses Pusher/Echo PrivateChannel stack)
        event(new TitanTalkMessageSent($message));

        // Notify non-muted members (database + optional mail/push via TitanTalkNewMessage)
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

        // Mention detection — mirrors NewChatObserver mention logic
        $mentionedUsers = $this->ttService->parseMentions($message->body, company()->id);
        foreach ($mentionedUsers as $mentioned) {
            if ($mentioned->id !== user()->id) {
                event(new TitanTalkMentionEvent($message, $mentioned));
            }
        }

        // For DM rooms: mirror to Worksuite UserChat inbox so /messages stays in sync
        if ($room->type === 'dm' && config('titantalk.mirror_dm_to_userchat', true)) {
            $otherMember = $members->first();
            if ($otherMember?->user) {
                $this->ttService->mirrorToUserChat($message, $otherMember->user->id);
            }
        }

        // Log to Communication module history (audit trail)
        $this->ttService->logToCommunication(
            companyId:  company()->id,
            fromUserId: user()->id,
            toUserId:   null,
            subject:    'TitanTalk message in #' . $room->name,
            body:       \Illuminate\Support\Str::limit($message->body, 500),
            roomId:     $room->id,
        );

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
