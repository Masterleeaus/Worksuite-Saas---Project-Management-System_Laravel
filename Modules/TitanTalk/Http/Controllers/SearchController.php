<?php

namespace Modules\TitanTalk\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanTalk\Models\TitanTalkMessage;
use Modules\TitanTalk\Models\TitanTalkRoom;

class SearchController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|min:2|max:200']);

        $q         = $request->q;
        $companyId = company()->id;
        $userId    = user()->id;

        // Search accessible rooms
        $rooms = TitanTalkRoom::accessibleByUser($userId, $companyId)
            ->where('name', 'like', '%' . $q . '%')
            ->limit(10)
            ->get(['id', 'name', 'type']);

        // Search messages in accessible rooms
        $accessibleRoomIds = TitanTalkRoom::accessibleByUser($userId, $companyId)->pluck('id');

        $messages = TitanTalkMessage::whereIn('room_id', $accessibleRoomIds)
            ->where('company_id', $companyId)
            ->where('body', 'like', '%' . $q . '%')
            ->whereNull('thread_parent_id')
            ->with(['author', 'room'])
            ->limit(20)
            ->get();

        // Search users (company-scoped)
        $users = User::where('company_id', $companyId)
            ->where('name', 'like', '%' . $q . '%')
            ->limit(10)
            ->get(['id', 'name', 'image', 'email']);

        return response()->json([
            'rooms'    => $rooms,
            'messages' => $messages,
            'users'    => $users,
        ]);
    }
}
