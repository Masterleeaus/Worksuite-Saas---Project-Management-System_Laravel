<?php

namespace App\Http\Controllers\Admin\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\Settings\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index(): View
    {
        if (!validatePermissions('settings')) {
            abort(403, 'Unauthorized access');
        }

        // Initialize defaults if not set
        $this->settingsService->initializeDefaultSettings();

        $settings = $this->settingsService->getGroupedSettings();

        return view('admin.Settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!validatePermissions('settings')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $settings = $request->except(['_token', '_method']);

        $this->settingsService->updateSettings($settings);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
            ]);
        }

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Settings updated');
    }
}
