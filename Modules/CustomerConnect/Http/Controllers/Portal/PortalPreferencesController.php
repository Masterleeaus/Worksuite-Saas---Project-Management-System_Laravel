<?php

namespace Modules\CustomerConnect\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\CustomerConnect\Entities\PortalPreference;

/**
 * PortalPreferencesController — customer updates notification preferences and preferred cleaner.
 */
class PortalPreferencesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the preferences form.
     */
    public function index()
    {
        $user = Auth::user();
        $prefs = PortalPreference::firstOrNew(['user_id' => $user->id]);

        return view('customerconnect::portal.preferences.index', compact('prefs'));
    }

    /**
     * Save preferences.
     */
    public function update(Request $request)
    {
        $request->validate([
            'preferred_cleaner_id'   => 'nullable|integer',
            'notify_email'           => 'boolean',
            'notify_sms'             => 'boolean',
            'special_instructions'   => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();

        PortalPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'preferred_cleaner_id' => $request->preferred_cleaner_id,
                'notify_email'         => $request->boolean('notify_email', true),
                'notify_sms'           => $request->boolean('notify_sms', false),
                'special_instructions' => $request->special_instructions,
            ]
        );

        return redirect()->route('customerconnect.portal.preferences.index')
            ->with('success', 'Preferences saved successfully.');
    }
}
