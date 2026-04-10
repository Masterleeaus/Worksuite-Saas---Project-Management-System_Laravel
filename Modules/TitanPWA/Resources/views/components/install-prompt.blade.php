{{--
    TitanPWA :: components/install-prompt
    ─────────────────────────────────────────────────────────────────────────
    "Add to Home Screen" install prompt banner.
    Shown automatically by pwa-register.js after 3 s if the browser fires
    the beforeinstallprompt event and the user hasn't installed or dismissed.

    Usage (include once, near the bottom of your <body>):
        @include('titanpwa::components.install-prompt')
--}}

<div id="titanpwa-install-banner"
     class="titanpwa-install-banner titanpwa-hidden"
     role="banner"
     aria-label="Install CleanSmartOS">

    <div class="titanpwa-install-content">
        <img src="{{ asset('vendor/titanpwa/icons/icon-96x96.png') }}"
             alt="{{ config('titanpwa.short_name', 'CleanSmartOS') }}"
             class="titanpwa-install-icon"
             width="48"
             height="48">

        <div class="titanpwa-install-text">
            <strong>Install {{ config('titanpwa.short_name', 'CleanSmartOS') }}</strong>
            <span>Add to your home screen for fast, offline access</span>
        </div>
    </div>

    <div class="titanpwa-install-actions">
        <button class="titanpwa-btn titanpwa-btn-primary"
                onclick="titanPWAInstall()"
                type="button">
            Install
        </button>
        <button class="titanpwa-btn titanpwa-btn-secondary"
                type="button"
                onclick="document.getElementById('titanpwa-install-banner').classList.add('titanpwa-hidden'); localStorage.setItem('titanpwa-dismiss-install','1');">
            Not now
        </button>
    </div>
</div>
