<?php

namespace Modules\TitanPWA\Http\Controllers;

use Illuminate\Routing\Controller;

/**
 * OfflinePageController
 *
 * Serves the offline fallback page.  The service worker pre-caches this URL
 * during the install phase and returns it whenever a navigation request fails
 * due to network unavailability.
 */
class OfflinePageController extends Controller
{
    public function index()
    {
        return view('titanpwa::offline');
    }
}
