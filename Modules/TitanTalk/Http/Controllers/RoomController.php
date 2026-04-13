<?php

namespace Modules\TitanTalk\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\TitanTalk\Models\TitanTalkRoom;
use Modules\TitanTalk\Models\TitanTalkRoomMember;
use Modules\TitanTalk\Services\TitanTalkService;

class RoomController extends AccountBaseController
{
    public function __construct(private readonly TitanTalkService $ttService)
    {
        parent::__construct();
        $this->pageTitle = 'TitanTalk';
    }

    public function index()
    {
        $companyId = company()->id;
        $userId    = user()->id;

        if (request()->filled('dm_user_id')) {
            $dmRoom = $this->ttService->resolveDmRoom($userId, (int) request('dm_user_id'), $companyId);
            return redirect()->route('titan.talk.room.show', $dmRoom->id);
        }

        $this->rooms = TitanTalkRoom::accessibleByUser($userId, $companyId)
            ->with(['roomMembers' => fn($q) => $q->where('user_id', $userId)])
            ->orderBy('name')
            ->get();

        $this->activeRoom = null;

        return view('titantalk::app', $this->data);
    }

    public function show(TitanTalkRoom $room)
    {
        $this->authorizeRoomAccess($room);

        $companyId = company()->id;
        $userId    = user()->id;

        $this->rooms = TitanTalkRoom::accessibleByUser($userId, $companyId)
            ->with(['roomMembers' => fn($q) => $q->where('user_id', $userId)])
            ->orderBy('name')
            ->get();

        $this->activeRoom = $room->load(['members', 'pins.message.author']);
        $this->messages   = $room->messages()
            ->with(['author', 'files', 'reactions'])
            ->orderBy('created_at')
            ->paginate(50);

        // Mark room as read
        TitanTalkRoomMember::where('room_id', $room->id)
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);

        return view('titantalk::app', $this->data);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'type'        => 'required|string|in:' . implode(',', TitanTalkRoom::ROOM_TYPES),
            'members'     => 'nullable|array',
            'members.*'   => 'integer|exists:users,id',
        ]);

        // DM rooms use TitanTalkService to enforce canonical slug and dedup
        if ($validated['type'] === 'dm') {
            $request->validate(['dm_user_id' => 'required|integer|exists:users,id']);
            $room = $this->ttService->resolveDmRoom(user()->id, (int) $request->dm_user_id, company()->id);

            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'room' => $room]);
            }

            return redirect()->route('titan.talk.room.show', $room->id);
        }

        // Ensure unique slug within company
        $baseSlug = \Illuminate\Support\Str::slug($validated['name']);
        $slug     = $baseSlug;
        $i        = 2;
        while (TitanTalkRoom::where('company_id', company()->id)->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$i}";
            $i++;
        }

        $room = TitanTalkRoom::create([
            'company_id'  => company()->id,
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'] ?? null,
            'type'        => $validated['type'],
            'created_by'  => user()->id,
        ]);

        // Add creator as owner
        TitanTalkRoomMember::create([
            'room_id' => $room->id,
            'user_id' => user()->id,
            'role'    => 'owner',
        ]);

        // Add initial members
        foreach (($validated['members'] ?? []) as $memberId) {
            if ($memberId !== user()->id) {
                TitanTalkRoomMember::firstOrCreate([
                    'room_id' => $room->id,
                    'user_id' => $memberId,
                ], ['role' => 'member']);
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'room' => $room]);
        }

        return redirect()->route('titan.talk.room.show', $room->id);
    }

    public function update(Request $request, TitanTalkRoom $room): JsonResponse
    {
        $this->authorizeRoomAdmin($room);

        $validated = $request->validate([
            'name'        => 'sometimes|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $room->update($validated);

        return response()->json(['status' => 'success', 'room' => $room]);
    }

    public function destroy(TitanTalkRoom $room): JsonResponse
    {
        $this->authorizeRoomAdmin($room);
        $room->delete();

        return response()->json(['status' => 'success']);
    }

    public function join(TitanTalkRoom $room): JsonResponse
    {
        if (in_array($room->type, ['private', 'dm'])) {
            return response()->json(['status' => 'error', 'message' => 'Cannot join private rooms directly.'], 403);
        }

        TitanTalkRoomMember::firstOrCreate([
            'room_id' => $room->id,
            'user_id' => user()->id,
        ], ['role' => 'member']);

        return response()->json(['status' => 'success']);
    }

    public function leave(TitanTalkRoom $room): JsonResponse
    {
        TitanTalkRoomMember::where('room_id', $room->id)
            ->where('user_id', user()->id)
            ->where('role', '!=', 'owner')
            ->delete();

        return response()->json(['status' => 'success']);
    }

    public function invite(Request $request, TitanTalkRoom $room): JsonResponse
    {
        $this->authorizeRoomAdmin($room);

        $request->validate([
            'user_ids'   => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        foreach ($request->user_ids as $userId) {
            TitanTalkRoomMember::firstOrCreate([
                'room_id' => $room->id,
                'user_id' => $userId,
            ], ['role' => 'member']);
        }

        return response()->json(['status' => 'success']);
    }

    public function removeMember(TitanTalkRoom $room, int $userId): JsonResponse
    {
        $this->authorizeRoomAdmin($room);

        TitanTalkRoomMember::where('room_id', $room->id)
            ->where('user_id', $userId)
            ->where('role', '!=', 'owner')
            ->delete();

        return response()->json(['status' => 'success']);
    }

    public function mute(TitanTalkRoom $room): JsonResponse
    {
        TitanTalkRoomMember::where('room_id', $room->id)
            ->where('user_id', user()->id)
            ->update(['is_muted' => true]);

        return response()->json(['status' => 'success']);
    }

    public function unmute(TitanTalkRoom $room): JsonResponse
    {
        TitanTalkRoomMember::where('room_id', $room->id)
            ->where('user_id', user()->id)
            ->update(['is_muted' => false]);

        return response()->json(['status' => 'success']);
    }

    public function unreadCounts(): JsonResponse
    {
        $userId    = user()->id;
        $companyId = company()->id;

        $rooms = TitanTalkRoom::accessibleByUser($userId, $companyId)
            ->with(['roomMembers' => fn($q) => $q->where('user_id', $userId)])
            ->get();

        $counts = $rooms->mapWithKeys(fn($room) => [
            $room->id => $room->unreadCountForUser($userId),
        ]);

        return response()->json(['counts' => $counts]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function authorizeRoomAccess(TitanTalkRoom $room): void
    {
        if ($room->company_id !== company()->id) {
            abort(403);
        }

        if (in_array($room->type, ['private', 'dm']) && !$room->isMember(user()->id)) {
            abort(403);
        }
    }

    private function authorizeRoomAdmin(TitanTalkRoom $room): void
    {
        if ($room->company_id !== company()->id) {
            abort(403);
        }

        $role = $room->getMemberRole(user()->id);

        if (!in_array($role, ['owner', 'admin'])) {
            abort(403);
        }
    }
}