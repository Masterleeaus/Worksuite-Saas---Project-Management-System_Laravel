<?php

namespace Modules\TitanTheme\Services;

use Modules\TitanTheme\Models\ThemePreset;

class ThemeService
{
    /**
     * Get the currently active preset for the current company, or null.
     */
    public function activePreset(): ?ThemePreset
    {
        return ThemePreset::where('is_active', true)->first();
    }

    /**
     * Generate a <style> block string with CSS variables from the active preset
     * (falls back to config defaults when no preset is active).
     */
    public function generateCssVariables(?ThemePreset $preset = null): string
    {
        $preset ??= $this->activePreset();
        $defaults = config('titantheme.defaults', []);
        $prefix   = config('titantheme.css_var_prefix', '--tt-');

        $vars = $preset
            ? $preset->toCssVariables()
            : $this->defaultCssVariables($prefix, $defaults);

        if ($preset && !empty($preset->custom_css)) {
            // Custom CSS will be injected separately.
        }

        $lines = [];
        foreach ($vars as $var => $value) {
            $lines[] = "    {$var}: {$value};";
        }

        $css = ":root {\n" . implode("\n", $lines) . "\n}";

        if ($preset && !empty($preset->custom_css)) {
            $css .= "\n\n" . $preset->custom_css;
        }

        return $css;
    }

    /**
     * Build default CSS variables from config defaults.
     */
    protected function defaultCssVariables(string $prefix, array $defaults): array
    {
        return array_filter([
            $prefix . 'primary'   => $defaults['primary_color']    ?? null,
            $prefix . 'secondary' => $defaults['secondary_color']  ?? null,
            $prefix . 'accent'    => $defaults['accent_color']     ?? null,
            $prefix . 'bg'        => $defaults['background_color'] ?? null,
            $prefix . 'text'      => $defaults['text_color']       ?? null,
            $prefix . 'font-head' => isset($defaults['heading_font'])
                ? "'{$defaults['heading_font']}', sans-serif" : null,
            $prefix . 'font-body' => isset($defaults['body_font'])
                ? "'{$defaults['body_font']}', sans-serif"   : null,
            $prefix . 'sidebar-w' => isset($defaults['sidebar_width'])
                ? $defaults['sidebar_width'] . 'px' : null,
            $prefix . 'header-h'  => isset($defaults['header_height'])
                ? $defaults['header_height'] . 'px' : null,
            $prefix . 'radius'    => isset($defaults['border_radius'])
                ? $defaults['border_radius'] . 'px' : null,
        ]);
    }

    /**
     * Activate a preset (deactivates all others for the company first).
     */
    public function activatePreset(ThemePreset $preset): void
    {
        ThemePreset::where('id', '!=', $preset->id)->update(['is_active' => false]);
        $preset->update(['is_active' => true]);
    }

    /**
     * Deactivate all presets (revert to defaults).
     */
    public function deactivateAll(): void
    {
        ThemePreset::query()->update(['is_active' => false]);
    }

    /**
     * Create a new preset from an array of theme settings.
     */
    public function createPreset(array $data, int $createdBy): ThemePreset
    {
        return ThemePreset::create(array_merge($data, ['created_by' => $createdBy]));
    }

    /**
     * Return list of available Google Fonts from config.
     */
    public function availableFonts(): array
    {
        return config('titantheme.fonts', []);
    }

    /**
     * Server-side CSS block builder from stored settings.
     *
     * Builds a full CSS custom-property block from the saved LiveCustomizer
     * setting() values (the raw CSS saved by LiveCustomizerController::apply())
     * merged with the active ThemePreset variables.  Suitable for SSR/caching.
     *
     * @return string  Ready-to-embed CSS string (no wrapping <style> tags).
     */
    public function generateCss(): string
    {
        $canUseSetting = function_exists('setting');
        $dashTheme     = $canUseSetting ? (setting('dash_theme') ?? 'default') : 'default';

        // Raw CSS block saved by the real-time LiveCustomizer panel.
        $rawCss = $canUseSetting ? setting($dashTheme . '_live_customizer', '') : '';

        // Structured CSS vars from the active ThemePreset model.
        $structuredCss = $this->generateCssVariables();

        if (!empty($rawCss)) {
            // Raw CSS takes precedence (last-write-wins in cascade).
            return $structuredCss . "\n\n" . $rawCss;
        }

        return $structuredCss;
    }
}
