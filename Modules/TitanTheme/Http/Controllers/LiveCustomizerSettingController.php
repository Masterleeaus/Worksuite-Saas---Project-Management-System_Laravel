<?php

namespace Modules\TitanTheme\Http\Controllers;

use App\Helpers\Classes\Helper;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;

/**
 * Admin settings panel for enabling/disabling the Live Customizer feature.
 *
 * Route: GET  account/admin/titan-theme/customiser/setting  → index()
 *        POST account/admin/titan-theme/customiser/setting  → update()
 */
class LiveCustomizerSettingController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('titantheme::titantheme.customizer_settings');
    }

    public function index()
    {
        abort_403(!$this->canManageThemeSettings());

        return view('titantheme::customizer.setting', $this->data);
    }

    public function update(Request $request)
    {
        abort_403(!$this->canManageThemeSettings());

        if (Helper::appIsNotDemo()) {
            setting([
                'show_live_customizer' => (int) $request->has('show_live_customizer'),
            ])->save();

            return redirect()->back()->with([
                'success' => __('titantheme::titantheme.customizer_settings_updated'),
                'type'    => 'success',
            ]);
        }

        return redirect()->back()->with([
            'message' => __('messages.demoRestrictedAction'),
            'type'    => 'error',
        ]);
    }

    protected function canManageThemeSettings(): bool
    {
        return in_array($this->user->permission('manage_theme_settings'), ['all', 'added', 'owned', 'both'], true)
            || $this->user->permission('manage_theme_setting') === 'all';
    }
}
