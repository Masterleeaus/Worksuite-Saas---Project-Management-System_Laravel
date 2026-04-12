{{--
    lqd-customizer-style-head.blade.php
    Inject TitanTheme CSS variables into every page <head>.
    Include via: @include('titantheme::lqd-customizer-style-head')

    Injection strategy (two layers, last-write-wins in cascade):
      1. ThemeService preset  — structured CSS variables from the active ThemePreset model.
      2. LiveCustomizer raw   — raw CSS block saved by LiveCustomizerController::apply()
         via the setting() helper (backward-compatible with original LiveCustomizer ext).
--}}
@php
    /** @var \Modules\TitanTheme\Services\ThemeService $themeService */
    $themeService = app(\Modules\TitanTheme\Services\ThemeService::class);
    $ttCss        = $themeService->generateCssVariables();

    // Backward-compat: raw CSS string saved by LiveCustomizerController::apply()
    $lqdRawCss    = setting((setting('dash_theme') ?? 'default') . '_live_customizer');
@endphp
<style id="titan-theme-vars">
{!! $ttCss !!}
</style>
@if (!empty($lqdRawCss))
<style id="lqd-customizer-style">
{!! $lqdRawCss !!}
</style>
@endif
