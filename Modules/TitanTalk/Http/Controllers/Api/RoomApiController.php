<?php

namespace Modules\TitanTalk\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\TitanTalk\Models\TitanTalkRoom;

class RoomApiController extends Controller
{
    public function index(): JsonResponse
    {
        $userId    = auth()->id();
        $companyId = auth()->user()?->company_id;

        if (!$companyId) {
            return response()->json(['data' => []]);
        }

        $rooms = TitanTalkRoom::accessibleByUser($userId, $companyId)
            ->with(['roomMembers' => fn($q) => $q->where('user_id', $userId)])
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $rooms]);
    }
}
