<?php

namespace Modules\TitanTheme\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\ThemeSetting;
use App\Scopes\CompanyScope;
use Illuminate\Http\Request;
use Modules\TitanTheme\Models\ThemePreset;
use Modules\TitanTheme\Services\ThemeService;

class ThemeController extends AccountBaseController
{
    public function __construct(protected ThemeService $themeService)
    {
        parent::__construct();
        $this->pageTitle = __('titantheme::titantheme.module_name');
    }

    /**
     * Theme presets index.
     */
    public function index()
    {
        abort_403(!$this->canViewThemeSettings());

        $this->presets       = ThemePreset::orderByDesc('created_at')->get();
        $this->activePreset  = $this->themeService->activePreset();
        $this->availableFonts = $this->themeService->availableFonts();

        return view('titantheme::presets.index', $this->data);
    }

    /**
     * Show create form.
     */
    public function create()
    {
        abort_403(!$this->canManageThemeSettings());

        $this->availableFonts = $this->themeService->availableFonts();
        $this->defaults       = config('titantheme.defaults', []);

        return view('titantheme::presets.create', $this->data);
    }

    /**
     * Store a new preset.
     */
    public function store(Request $request)
    {
        abort_403(!$this->canManageThemeSettings());

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string|max:500',
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

        return Reply::successWithData(
            __('titantheme::titantheme.preset_created'),
            ['redirectUrl' => route('titantheme.presets.index')]
        );
    }

    /**
     * Show edit form.
     */
    public function edit(int $id)
    {
        abort_403(!$this->canManageThemeSettings());

        $this->preset         = ThemePreset::findOrFail($id);
        $this->availableFonts = $this->themeService->availableFonts();

        return view('titantheme::presets.edit', $this->data);
    }

    /**
     * Update a preset.
     */
    public function update(Request $request, int $id)
    {
        abort_403(!$this->canManageThemeSettings());

        $preset = ThemePreset::findOrFail($id);

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string|max:500',
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

        $preset->update($data);

        return Reply::successWithData(
            __('titantheme::titantheme.preset_updated'),
            ['redirectUrl' => route('titantheme.presets.index')]
        );
    }

    /**
     * Delete a preset.
     */
    public function destroy(int $id)
    {
        abort_403(!$this->canManageThemeSettings());

        ThemePreset::findOrFail($id)->delete();

        return Reply::success(__('titantheme::titantheme.preset_deleted'));
    }

    /**
     * Activate a preset.
     */
    public function activate(int $id)
    {
        abort_403(!$this->canManageThemeSettings());

        $preset = ThemePreset::findOrFail($id);
        $this->themeService->activatePreset($preset);

        return Reply::success(__('titantheme::titantheme.preset_activated'));
    }

    /**
     * Deactivate all presets (revert to defaults).
     */
    public function deactivate()
    {
        abort_403(!$this->canManageThemeSettings());

        $this->themeService->deactivateAll();

        return Reply::success(__('titantheme::titantheme.presets_deactivated'));
    }

    /**
     * Return the inline CSS for the current active preset (used in <head>).
     */
    public function cssVariables()
    {
        $css = $this->themeService->generateCssVariables();

        return response($css, 200, ['Content-Type' => 'text/css']);
    }

    protected function canViewThemeSettings(): bool
    {
        return in_array('titantheme', user_modules(), true)
            && (
                in_array($this->user->permission('view_theme_settings'), ['all', 'added', 'owned', 'both'], true)
                || $this->user->permission('manage_theme_setting') === 'all'
            );
    }

    protected function canManageThemeSettings(): bool
    {
        $hasPermission = in_array('titantheme', user_modules(), true)
            && (
                in_array($this->user->permission('manage_theme_settings'), ['all', 'added', 'owned', 'both'], true)
                || $this->user->permission('manage_theme_setting') === 'all'
            );

        if (!$hasPermission) {
            return false;
        }

        $superAdminThemeSetting = ThemeSetting::withoutGlobalScope(CompanyScope::class)
            ->where('panel', 'superadmin')
            ->whereNull('company_id')
            ->first();

        if (!$superAdminThemeSetting || !$superAdminThemeSetting->restrict_admin_theme_change) {
            return true;
        }

        return (bool) ($this->user->is_superadmin ?? false);
    }
}
