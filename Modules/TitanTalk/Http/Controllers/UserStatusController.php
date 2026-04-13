<?php

namespace Modules\TitanTalk\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanTalk\Models\TitanTalkUserStatus;

class UserStatusController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'presence'     => 'required|in:online,away,offline',
            'status_emoji' => 'nullable|string|max:32',
            'status_text'  => 'nullable|string|max:100',
            'expires_at'   => 'nullable|date',
        ]);

        TitanTalkUserStatus::updateOrCreate(
            ['user_id' => user()->id],
            [
                'presence'      => $request->presence,
                'status_emoji'  => $request->status_emoji,
                'status_text'   => $request->status_text,
                'expires_at'    => $request->expires_at,
                'last_seen_at'  => now(),
            ]
        );

        return response()->json(['status' => 'success']);
    }

    public function show(User $user): JsonResponse
    {
        $status = TitanTalkUserStatus::firstOrNew(['user_id' => $user->id]);

        return response()->json([
            'user_id'      => $user->id,
            'name'         => $user->name,
            'presence'     => $status->presence ?? 'offline',
            'status_emoji' => $status->status_emoji,
            'status_text'  => $status->status_text,
            'last_seen_at' => $status->last_seen_at?->diffForHumans(),
        ]);
    }
}
