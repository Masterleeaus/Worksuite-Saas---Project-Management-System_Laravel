<?php

namespace Modules\TitanTheme\Http\Controllers;

use App\Helper\Reply;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanTheme\Models\ThemePreset;
use Modules\TitanTheme\Services\ThemeService;

class LiveCustomizerController extends AccountBaseController
{
    public function __construct(protected ThemeService $themeService)
    {
        parent::__construct();
        $this->pageTitle = __('titantheme::titantheme.live_customizer');
    }

    /**
     * Show the live customizer interface.
     */
    public function index()
    {
        abort_403(!$this->user->permission('manage_theme_settings'));

        $this->activePreset   = $this->themeService->activePreset();
        $this->availableFonts = $this->themeService->availableFonts();
        $this->defaults       = config('titantheme.defaults', []);
        $this->cssVars        = $this->themeService->generateCssVariables($this->activePreset);

        return view('titantheme::customizer.index', $this->data);
    }

    /**
     * Live preview endpoint — return CSS variables for the submitted values
     * without persisting them.
     */
    public function preview(Request $request)
    {
        abort_403(!$this->user->permission('manage_theme_settings'));

        // Build a temporary (unsaved) preset from the submitted values.
        $tempPreset = new ThemePreset($request->only([
            'primary_color', 'secondary_color', 'accent_color',
            'background_color', 'text_color',
            'heading_font', 'body_font',
            'sidebar_width', 'header_height', 'border_radius',
            'custom_css',
        ]));

        $css = $this->themeService->generateCssVariables($tempPreset);

        return Reply::successWithData('ok', ['css' => $css]);
    }

    /**
     * Save the customiser values as a new (or update existing active) preset.
     */
    public function save(Request $request)
    {
        abort_403(!$this->user->permission('manage_theme_settings'));

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'primary_color'    => 'nullable|string|max:20',
            'secondary_color'  => 'nullable|string|max:20',
            'accent_color'     => 'nullable|string|max:20',
            'background_color' => 'nullable|string|max:20',
            'text_color'       => 'nullable|string|max:20',
            'heading_font'     => 'nullable|string|max:100',
            'body_font'        => 'nullable|string|max:100',
            'sidebar_width'    => 'nullable|integer|min:160|max:400',
            'header_height'    => 'nullable|integer|min:40|max:120',
            'border_radius'    => 'nullable|integer|min:0|max:50',
            'custom_css'       => 'nullable|string',
        ]);

        $preset = $this->themeService->createPreset($data, $this->user->id);
        $this->themeService->activatePreset($preset);

        return Reply::successWithData(
            __('titantheme::titantheme.theme_saved'),
            ['presetId' => $preset->id]
        );
    }

    /**
     * Real-time CSS variable application endpoint (LiveCustomizer v1.3.0 protocol).
     *
     * Saves the raw CSS-variable string and font configuration via the WorkSuite
     * setting() helper so that lqd-customizer-style-head injects them immediately
     * on the next page load.  Setting keys are intentionally backward-compatible
     * with the original LiveCustomizer extension.
     *
     * POST account/titan-theme/customiser/apply
     * Body params:
     *   style  — full CSS custom-property block (string)
     *   fonts  — font configuration object {fontBody, fontHeading}
     *   clear  — (optional) truthy to discard and revert to defaults
     */
    public function apply(Request $request)
    {
        abort_403(!$this->user->permission('manage_theme_settings'));

        if (Helper::appIsNotDemo()) {
            $dashTheme = setting('dash_theme') ?? 'default';

            setting([
                $dashTheme . '_live_customizer'       => $request->get('style'),
                $dashTheme . '_live_customizer_fonts' => $request->get('fonts'),
                'show_live_customizer'                => 0,
            ])->save();

            $message = $request->get('clear')
                ? __('titantheme::titantheme.changes_discarded')
                : __('titantheme::titantheme.theme_updated');

            return response()->json([
                'message' => $message,
                'status'  => 'success',
            ]);
        }

        return response()->json([
            'message' => __('messages.demoRestrictedAction'),
            'status'  => 'error',
        ], 422);
    }
}
