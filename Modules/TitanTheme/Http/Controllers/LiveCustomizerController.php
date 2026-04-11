<?php

namespace Modules\TitanTheme\Http\Controllers;

use App\Helper\Reply;
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
}
