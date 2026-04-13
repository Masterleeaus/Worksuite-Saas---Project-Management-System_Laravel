<?php
/**
 * TitanTalk route bridge.
 * Loaded by the Titan route-loader if present.
 * The actual routes are registered by TitanTalkServiceProvider via RouteServiceProvider.
 * This file exists as an explicit bridge entry-point for Titan infrastructure.
 */

use Illuminate\Support\Facades\Route;
use Modules\TitanTalk\Http\Controllers\RoomController;
use Modules\TitanTalk\Http\Controllers\MessageController;

if (!class_exists(\Modules\TitanTalk\Providers\TitanTalkServiceProvider::class)) {
    return;
}

// All routes are registered by TitanTalkServiceProvider.
// Re-exposing an alias entry here for Titan route loader compatibility.
Route::middleware(['web', 'auth'])
    ->prefix('account/titan-talk')
    ->name('titan.talk.')
    ->group(function () {
        // This file intentionally delegates to the module's own RouteServiceProvider.
        // The service provider registers: Modules/TitanTalk/Routes/web.php
    });
