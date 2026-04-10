<?php

namespace Modules\FSMCore\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * WorkerAuthController
 *
 * Provides API token authentication for field workers.
 * Field workers use a mobile app and authenticate via personal access tokens
 * (Laravel Sanctum). This mirrors the reference app's v1/AuthController pattern.
 */
class WorkerAuthController extends Controller
{
    /**
     * POST /api/fsm/v1/auth/login
     *
     * Accepts JSON: { "email": "...", "password": "..." }
     * Returns: { "token": "...", "worker": { id, name, email }, "expires_at": "..." }
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        /** @var User|null $user */
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        if (isset($user->status) && in_array($user->status, ['deactive', 'inactive'], true)) {
            return response()->json(['message' => 'Account disabled.'], 403);
        }

        $tokenName = 'fsm-worker-' . Str::slug($user->name) . '-' . $user->id;
        $expiry    = now()->addYear();

        // Revoke previous FSM worker tokens so only one is active at a time.
        $user->tokens()->where('name', 'like', 'fsm-worker-%')->delete();

        $token = $user->createToken($tokenName, ['*'], $expiry)->plainTextToken;

        return response()->json([
            'token'      => $token,
            'expires_at' => $expiry->toIso8601String(),
            'worker'     => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * POST /api/fsm/v1/auth/logout
     *
     * Revokes the current access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * GET /api/fsm/v1/auth/me
     *
     * Returns the currently authenticated worker's profile.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ]);
    }
}
