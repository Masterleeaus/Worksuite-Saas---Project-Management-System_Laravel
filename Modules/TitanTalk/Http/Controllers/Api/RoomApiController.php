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

        // Require authenticated user with a valid company context
        if (!$userId || !$companyId) {
            return response()->json(['error' => 'Unauthenticated or no company context.'], 401);
        }

        // accessibleByUser already scopes by company_id, preventing cross-tenant leakage
        $rooms = TitanTalkRoom::accessibleByUser($userId, $companyId)
            ->with(['roomMembers' => fn($q) => $q->where('user_id', $userId)])
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $rooms]);
    }
}
