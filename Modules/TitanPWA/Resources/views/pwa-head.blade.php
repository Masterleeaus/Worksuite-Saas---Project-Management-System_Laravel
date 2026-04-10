{{--
    TitanPWA :: pwa-head
    ─────────────────────────────────────────────────────────────────────────
    Include this partial inside your <head> element on every page that should
    support PWA features.

    Usage:
        @include('titanpwa::pwa-head')
        OR
        @includeIf('titanpwa::pwa-head')
--}}

{{-- Web App Manifest --}}
<link rel="manifest" href="{{ route('titanpwa.manifest') }}">

{{-- Theme colour (matches manifest theme_color) --}}
<meta name="theme-color" content="{{ config('titanpwa.theme_color', '#1a5276') }}">

{{-- Android / Chrome PWA flags --}}
<meta name="mobile-web-app-capable" content="yes">

{{-- iOS / Safari PWA flags --}}
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ config('titanpwa.short_name', 'CleanSmartOS') }}">

{{-- Apple Touch Icons --}}
<link rel="apple-touch-icon" href="{{ asset('vendor/titanpwa/icons/icon-192x192.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('vendor/titanpwa/icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendor/titanpwa/icons/icon-192x192.png') }}">
<link rel="apple-touch-startup-image"         href="{{ asset('vendor/titanpwa/icons/icon-512x512.png') }}">

{{-- Favicon (fall-through to core favicon if not published) --}}
@if (file_exists(public_path('vendor/titanpwa/icons/favicon.ico')))
<link rel="icon" type="image/x-icon" href="{{ asset('vendor/titanpwa/icons/favicon.ico') }}">
@endif

{{-- PWA CSS --}}
<link rel="stylesheet" href="{{ asset('vendor/titanpwa/css/pwa.css') }}">
