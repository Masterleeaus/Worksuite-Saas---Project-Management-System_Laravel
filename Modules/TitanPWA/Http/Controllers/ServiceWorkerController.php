<?php

namespace Modules\TitanPWA\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * ServiceWorkerController
 *
 * Serves the TitanPWA service worker JavaScript file from the root URL path
 * (`/titanpwa-sw.js`) so that the service worker can claim the full site scope.
 *
 * The recommended approach is to publish the file to `public/` via:
 *   php artisan vendor:publish --tag=titanpwa-sw
 *
 * This controller acts as a fallback when the file has not yet been published.
 */
class ServiceWorkerController extends Controller
{
    /**
     * Serve the service worker JS file.
     */
    public function serve(): Response
    {
        // Prefer the already-published file for performance
        $publicFile = public_path('titanpwa-sw.js');
        if (file_exists($publicFile)) {
            return response(file_get_contents($publicFile), 200)
                ->header('Content-Type', 'application/javascript')
                ->header('Service-Worker-Allowed', '/')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        // Fallback: serve from module Resources/
        $moduleFile = module_path('TitanPWA', 'Resources/js/titanpwa-sw.js');
        if (file_exists($moduleFile)) {
            return response(file_get_contents($moduleFile), 200)
                ->header('Content-Type', 'application/javascript')
                ->header('Service-Worker-Allowed', '/')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        return response('/* TitanPWA service worker not found — run: php artisan vendor:publish --tag=titanpwa-sw */', 404)
            ->header('Content-Type', 'application/javascript');
    }
}
