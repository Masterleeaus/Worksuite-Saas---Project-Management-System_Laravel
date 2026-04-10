<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanPWA\Http\Controllers\ManifestController;
use Modules\TitanPWA\Http\Controllers\ServiceWorkerController;
use Modules\TitanPWA\Http\Controllers\OfflinePageController;

/*
|--------------------------------------------------------------------------
| TitanPWA Web Routes
|--------------------------------------------------------------------------
|
| manifest.json   → served with correct MIME type (application/manifest+json)
| titanpwa-sw.js  → served from root scope for correct SW registration
| offline.html    → served for the offline fallback page
|
*/

// Manifest — no auth required; must be publicly accessible
Route::get('/titanpwa/manifest.json', [ManifestController::class, 'serve'])
    ->name('titanpwa.manifest');

// Service Worker — no auth, root scope
// NOTE: The SW file is also published to public/ via vendor:publish.
//       This route provides a dynamic fallback in case the file has not been published yet.
Route::get('/titanpwa-sw.js', [ServiceWorkerController::class, 'serve'])
    ->name('titanpwa.sw');

// Offline fallback page (cached by SW and shown when network is unavailable)
Route::get('/titanpwa/offline', [OfflinePageController::class, 'index'])
    ->name('titanpwa.offline');
