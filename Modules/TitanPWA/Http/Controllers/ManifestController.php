<?php

namespace Modules\TitanPWA\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * ManifestController
 *
 * Serves the Web App Manifest (manifest.json) dynamically from the titanpwa
 * config so that app name, colours, icons, etc., can be changed via .env /
 * config without re-publishing static files.
 */
class ManifestController extends Controller
{
    /**
     * Serve the Web App Manifest.
     *
     * The response uses the standard MIME type `application/manifest+json` and
     * sets a short Cache-Control header so browsers refresh the manifest daily.
     */
    public function serve(): JsonResponse
    {
        $cfg = config('titanpwa', []);

        $icons = [];
        foreach ($cfg['icons'] ?? [] as $size => $path) {
            $icons[] = [
                'src'   => asset($path),
                'sizes' => $size,
                'type'  => 'image/png',
            ];
        }

        // Add maskable version of the largest icon
        $largest = end($cfg['icons'] ?? []);
        if ($largest) {
            $icons[] = [
                'src'     => asset($largest),
                'sizes'   => '512x512',
                'type'    => 'image/png',
                'purpose' => 'maskable',
            ];
        }

        $manifest = [
            'name'             => $cfg['name']             ?? 'CleanSmartOS',
            'short_name'       => $cfg['short_name']       ?? 'CleanSmartOS',
            'description'      => $cfg['description']      ?? 'Cleaning Operations Management System',
            'start_url'        => $cfg['start_url']        ?? '/',
            'scope'            => $cfg['scope']            ?? '/',
            'display'          => $cfg['display']          ?? 'standalone',
            'orientation'      => $cfg['orientation']      ?? 'portrait-primary',
            'theme_color'      => $cfg['theme_color']      ?? '#1a5276',
            'background_color' => $cfg['background_color'] ?? '#ffffff',
            'categories'       => $cfg['categories']       ?? ['business', 'utilities'],
            'icons'            => $icons,
            'shortcuts'        => [
                [
                    'name'      => 'My Jobs',
                    'short_name'=> 'Jobs',
                    'url'       => '/jobs',
                    'icons'     => [['src' => asset('vendor/titanpwa/icons/icon-96x96.png'), 'sizes' => '96x96']],
                ],
            ],
        ];

        return response()
            ->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
