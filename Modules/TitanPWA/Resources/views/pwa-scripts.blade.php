{{--
    TitanPWA :: pwa-scripts
    ─────────────────────────────────────────────────────────────────────────
    Include this partial just before </body> on every page.
    It loads the client-side registration + helpers and injects a tiny
    inline block so the VAPID key is available before the external script.

    Usage:
        @include('titanpwa::pwa-scripts')
--}}

<script>
    window._titanPWAConfig = {
        vapidPublicKey: "{{ config('titanpwa.vapid_public_key', '') }}",
        swPath:         "{{ config('titanpwa.sw_path', '/titanpwa-sw.js') }}",
        swScope:        "{{ config('titanpwa.sw_scope', '/') }}",
    };
</script>
<script src="{{ asset('vendor/titanpwa/js/pwa-register.js') }}" defer></script>
