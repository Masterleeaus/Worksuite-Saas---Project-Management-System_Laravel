<?php

namespace Modules\TitanTheme\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanTheme\Services\WhiteLabelService;

class WhiteLabelController extends AccountBaseController
{
    public function __construct(protected WhiteLabelService $whiteLabelService)
    {
        parent::__construct();
        $this->pageTitle = __('titantheme::titantheme.white_label');
    }

    /**
     * Show the white-label settings page.
     */
    public function index()
    {
        abort_403(!$this->user->permission('manage_white_label'));

        $this->logoUrl        = $this->whiteLabelService->assetUrl(
            $this->whiteLabelService->getSetting('logo_path', '')
        );
        $this->faviconUrl     = $this->whiteLabelService->assetUrl(
            $this->whiteLabelService->getSetting('favicon_path', '')
        );
        $this->loginBgUrl     = $this->whiteLabelService->assetUrl(
            $this->whiteLabelService->getSetting('login_bg_path', '')
        );
        $this->emailHeaderUrl = $this->whiteLabelService->assetUrl(
            $this->whiteLabelService->getSetting('email_header_path', '')
        );

        return view('titantheme::settings.white-label', $this->data);
    }

    /**
     * Save white-label branding settings.
     */
    public function update(Request $request)
    {
        abort_403(!$this->user->permission('manage_white_label'));

        $settings = [];

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|max:2048']);
            $settings['logo_path'] = $this->whiteLabelService->storeLogo($request->file('logo'));
        }

        if ($request->hasFile('favicon')) {
            $request->validate(['favicon' => 'file|mimes:ico,png,svg|max:512']);
            $settings['favicon_path'] = $this->whiteLabelService->storeFavicon($request->file('favicon'));
        }

        if ($request->hasFile('login_bg')) {
            $request->validate(['login_bg' => 'image|max:4096']);
            $settings['login_bg_path'] = $this->whiteLabelService->storeLoginBackground($request->file('login_bg'));
        }

        if ($request->hasFile('email_header')) {
            $request->validate(['email_header' => 'image|max:2048']);
            $settings['email_header_path'] = $this->whiteLabelService->storeEmailHeader($request->file('email_header'));
        }

        // Text-based settings
        foreach (['app_name', 'support_email', 'custom_domain'] as $field) {
            if ($request->filled($field)) {
                $settings[$field] = $request->input($field);
            }
        }

        if (!empty($settings)) {
            $this->whiteLabelService->saveSettings($settings);
        }

        return Reply::success(__('titantheme::titantheme.white_label_saved'));
    }
}
