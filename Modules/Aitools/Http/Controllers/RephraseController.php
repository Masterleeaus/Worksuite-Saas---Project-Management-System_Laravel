<?php

namespace Modules\Aitools\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Aitools\Entities\AiToolsUsageHistory;

class RephraseController extends Controller
{
    public function rephrase(Request $request): JsonResponse
    {
        $data = $request->validate([
            'text' => ['required', 'string', 'max:8000'],
            'language' => ['nullable', 'string', 'max:50'],
        ]);

        $text = trim($data['text']);
        $language = $data['language'] ?? 'English';

        // Build a conservative, UI-safe prompt.
        $prompt = "Rephrase the following text to be clearer and more professional while preserving the original meaning. "
            . "Keep formatting simple. Return ONLY the rewritten text.\n\nTEXT:\n" . $text;

        // Proxy to Titan Zero if available.
        try {
            /** @var \Modules\TitanZero\Services\TitanZeroService $tz */
            $tz = app(\Modules\TitanZero\Services\TitanZeroService::class);

            $userId = auth()->id();
            $companyId = null;
            try {
                if (function_exists('company') && company()) {
                    $companyId = company()->id;
                } elseif (auth()->user() && isset(auth()->user()->company_id)) {
                    $companyId = auth()->user()->company_id;
                }
            } catch (\Throwable $e) {
                $companyId = null;
            }

            $result = $tz->generate(
                $prompt,
                $language,
                512,
                0.4,
                1,
                $userId,
                $companyId,
                null
            );

            if (!($result['success'] ?? false)) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? __('Unable to rephrase text right now.'),
                ], 422);
            }

            $out = trim((string)($result['text'] ?? ''));
            $tokens = (int)($result['tokens'] ?? 0);

            // Lightweight usage row to satisfy super-admin token summary views.
            try {
                AiToolsUsageHistory::create([
                    'company_id' => $companyId,
                    'user_id' => $userId,
                    'total_tokens' => $tokens,
                    'prompt' => substr($text, 0, 500),
                ]);
            } catch (\Throwable $e) {
                // do nothing
            }

            return response()->json([
                'success' => true,
                'text' => $out,
                'tokens' => $tokens,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => __('Unable to rephrase text right now.'),
            ], 500);
        }
    }
}
