<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\BusinessSettingsModule\Entities\SettingsTutorials;

class IntegrationController extends Controller
{
    public function checklist(Request $request): JsonResponse
    {
        $companyId = $this->resolveCompanyId();

        if (empty($companyId)) {
            return response()->json(['message' => 'Unable to resolve company context.'], 422);
        }

        $businessSettings = BusinessSettings::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where('settings_type', 'business_information')
            ->pluck('live_values', 'key_name');

        $profileComplete = !empty($businessSettings['business_name'] ?? $businessSettings['company_name'] ?? null);
        $contactComplete = !empty($businessSettings['business_email'] ?? $businessSettings['company_email'] ?? null)
            && !empty($businessSettings['business_phone'] ?? $businessSettings['company_phone'] ?? null);

        $onboardingAnswers = SettingsTutorials::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where('platform', 'titan_zero')
            ->whereNotNull('options')
            ->exists();

        $items = [
            [
                'title' => 'Company profile',
                'summary' => 'Set your company name and brand details.',
                'completed' => $profileComplete,
                'link' => Route::has('admin.business-settings.get-business-information')
                    ? route('admin.business-settings.get-business-information')
                    : null,
            ],
            [
                'title' => 'Company contacts',
                'summary' => 'Set the primary company email and phone details.',
                'completed' => $contactComplete,
                'link' => Route::has('admin.business-settings.get-business-information')
                    ? route('admin.business-settings.get-business-information')
                    : null,
            ],
            [
                'title' => 'Titan Zero onboarding',
                'summary' => 'Save onboarding answers for Titan Zero assistants and CMS sync.',
                'completed' => $onboardingAnswers,
                'link' => Route::has('checklist') ? route('checklist') : null,
            ],
        ];

        $completedCount = collect($items)->where('completed', true)->count();

        return response()->json([
            'title' => 'Business settings onboarding',
            'summary' => 'Business settings tasks shown in Worksuite checklist surfaces.',
            'completed' => $completedCount === count($items),
            'progress' => [
                'completed' => $completedCount,
                'total' => count($items),
            ],
            'items' => $items,
        ]);
    }

    public function saveOnboarding(Request $request): JsonResponse
    {
        $companyId = $this->resolveCompanyId();
        $userId = $this->resolveUserId();

        if (empty($companyId) || empty($userId)) {
            return response()->json(['message' => 'Unable to resolve tenant or user context.'], 422);
        }

        $data = $request->validate([
            'answers' => 'required|array',
        ]);

        SettingsTutorials::withoutGlobalScopes()->updateOrCreate(
            [
                'company_id' => $companyId,
                'user_id' => $userId,
                'platform' => 'titan_zero',
            ],
            [
                'options' => $data['answers'],
            ]
        );

        return response()->json([
            'saved' => true,
            'redirect_to' => Route::has('checklist') ? route('checklist') : null,
        ]);
    }

    public function cmsSnapshot(): JsonResponse
    {
        $companyId = $this->resolveCompanyId();

        if (empty($companyId)) {
            return response()->json(['message' => 'Unable to resolve company context.'], 422);
        }

        $companyProfile = BusinessSettings::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where('settings_type', 'business_information')
            ->get(['key_name', 'live_values'])
            ->pluck('live_values', 'key_name');

        $bookingPreferences = BusinessSettings::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where('settings_type', 'booking_setup')
            ->get(['key_name', 'live_values'])
            ->pluck('live_values', 'key_name');

        $onboardingAnswers = SettingsTutorials::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where('platform', 'titan_zero')
            ->orderByDesc('id')
            ->value('options') ?? [];

        $platformDefaults = BusinessSettings::withoutGlobalScopes()
            ->whereNull('company_id')
            ->whereIn('settings_type', ['business_information', 'booking_setup'])
            ->get(['settings_type', 'key_name', 'live_values'])
            ->groupBy('settings_type')
            ->map(fn ($rows) => $rows->pluck('live_values', 'key_name'));

        return response()->json([
            'company_id' => $companyId,
            'company_profile' => $companyProfile,
            'onboarding_answers' => $onboardingAnswers,
            'booking_preferences' => $bookingPreferences,
            'platform_defaults' => $platformDefaults,
        ]);
    }

    private function resolveCompanyId(): ?int
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return null;
        }

        if (isset($authUser->company_id) && !empty($authUser->company_id)) {
            return (int) $authUser->company_id;
        }

        if (isset($authUser->user) && isset($authUser->user->company_id) && !empty($authUser->user->company_id)) {
            return (int) $authUser->user->company_id;
        }

        if (function_exists('user')) {
            $resolvedUser = user();
            if ($resolvedUser && isset($resolvedUser->company_id) && !empty($resolvedUser->company_id)) {
                return (int) $resolvedUser->company_id;
            }
        }

        return null;
    }

    private function resolveUserId(): ?int
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return null;
        }

        if (isset($authUser->id) && !empty($authUser->id) && !isset($authUser->user_id)) {
            return (int) $authUser->id;
        }

        if (isset($authUser->user_id) && !empty($authUser->user_id)) {
            return (int) $authUser->user_id;
        }

        if (isset($authUser->user) && isset($authUser->user->id) && !empty($authUser->user->id)) {
            return (int) $authUser->user->id;
        }

        if (function_exists('user') && user()) {
            return (int) user()->id;
        }

        return null;
    }
}

