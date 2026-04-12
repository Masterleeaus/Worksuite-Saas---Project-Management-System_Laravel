{{--
    lqd-customizer-style-head.blade.php
    Inject TitanTheme CSS variables into every page <head>.
    Include via: @include('titantheme::lqd-customizer-style-head')
--}}
@php
    /** @var \Modules\TitanTheme\Services\ThemeService $themeService */
    $themeService = app(\Modules\TitanTheme\Services\ThemeService::class);
    $ttCss = $themeService->generateCssVariables();
@endphp
<style id="titan-theme-vars">
{!! $ttCss !!}
</style>
