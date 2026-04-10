{{--
    TitanPWA :: components/update-banner
    ─────────────────────────────────────────────────────────────────────────
    "New version available" banner shown by pwa-register.js when the service
    worker detects an updated version waiting to activate.

    Usage (include once, near the TOP of your <body>):
        @include('titanpwa::components.update-banner')
--}}

<div id="titanpwa-update-banner"
     class="titanpwa-update-banner titanpwa-hidden"
     role="alert"
     aria-live="polite">

    <span>🔄 A new version of {{ config('titanpwa.short_name', 'CleanSmartOS') }} is available.</span>

    <button class="titanpwa-btn titanpwa-btn-primary titanpwa-update-refresh"
            type="button"
            aria-label="Refresh to update">
        Refresh now
    </button>

    <button class="titanpwa-btn titanpwa-btn-secondary"
            type="button"
            onclick="this.closest('#titanpwa-update-banner').classList.add('titanpwa-hidden')"
            aria-label="Dismiss update notification">
        Later
    </button>
</div>
