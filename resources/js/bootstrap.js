window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo + Laravel Reverb
 *
 * Laravel Reverb is our self-hosted WebSocket server. It speaks the Pusher
 * protocol, so pusher-js is still used as the transport layer — no extra
 * client package required.
 *
 * Environment variables (set in .env or webpack.mix.js):
 *   VITE_REVERB_APP_KEY   / MIX_REVERB_APP_KEY
 *   VITE_REVERB_HOST      / MIX_REVERB_HOST
 *   VITE_REVERB_PORT      / MIX_REVERB_PORT
 *   VITE_REVERB_SCHEME    / MIX_REVERB_SCHEME
 *
 * Start the server: php artisan reverb:start
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Support both Vite (VITE_) and Laravel Mix (MIX_) env prefixes.
const reverbKey    = process.env.VITE_REVERB_APP_KEY    ?? process.env.MIX_REVERB_APP_KEY    ?? 'worksuite-reverb-key';
const reverbHost   = process.env.VITE_REVERB_HOST       ?? process.env.MIX_REVERB_HOST       ?? 'localhost';
const reverbPort   = process.env.VITE_REVERB_PORT       ?? process.env.MIX_REVERB_PORT       ?? 8080;
const reverbScheme = process.env.VITE_REVERB_SCHEME     ?? process.env.MIX_REVERB_SCHEME     ?? 'http';

window.Echo = new Echo({
    broadcaster:    'reverb',
    key:            reverbKey,
    wsHost:         reverbHost,
    wsPort:         reverbScheme === 'https' ? 443 : reverbPort,
    wssPort:        443,
    forceTLS:       reverbScheme === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats:   true,
});
