{{--
    TitanPWA :: components/offline-indicator
    ─────────────────────────────────────────────────────────────────────────
    Persistent offline/online status banner + connection dot.
    Shown/hidden by pwa-register.js based on navigator.onLine.

    Usage (include once, near the bottom of your <body>):
        @include('titanpwa::components.offline-indicator')
--}}

{{-- Offline Banner (hidden when online) --}}
<div id="titanpwa-offline-banner"
     class="titanpwa-offline-banner titanpwa-hidden"
     role="alert"
     aria-live="polite"
     aria-atomic="true">

    <span class="titanpwa-offline-icon" aria-hidden="true">⚡</span>

    <span class="titanpwa-offline-text">You are offline</span>

    <span class="titanpwa-queue-badge" style="display:none">
        <span class="titanpwa-queue-count">0</span>
        action(s) pending sync
    </span>
</div>

{{-- Connection status dot (always rendered, colour changes) --}}
<div id="titanpwa-online-indicator"
     class="titanpwa-status-dot titanpwa-online"
     title="Online"
     aria-label="Connection status: online"
     role="status">
</div>
