<?php

/**
 * routes/Titan/filament.routes.php
 *
 * Titan panel route bootstrap.
 *
 * This file is loaded by RouteServiceProvider::mapTitanRoutes() and
 * bootstraps the Filament panel at /titan.
 *
 * Filament registers its own internal routes through the PanelProvider, so
 * this file primarily documents the entry-point and guards against conflicts
 * with existing Worksuite routes (/dashboard, /home, /admin, /account/*).
 *
 * DO NOT modify routes/web.php.
 */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Titan Panel Entry Guard
|--------------------------------------------------------------------------
| Ensures /titan/* is exclusively owned by the Filament panel.
| All routes below redirect any mis-directed traffic back to the panel home.
*/

// Redirect bare /titan to panel dashboard (Filament handles this internally,
// but an explicit route prevents 404s when the panel is not yet booted).
Route::middleware(['web'])->group(function () {

    Route::get('/titan', function () {
        if (!class_exists(\Filament\Facades\Filament::class)) {
            abort(503, 'Titan panel is not yet available. Please run: composer require filament/filament && php artisan filament:install --panels');
        }

        if (Route::has('filament.titan.pages.command-centre')) {
            return redirect()->route('filament.titan.pages.command-centre');
        }

        return redirect()->route('login');
    })->name('titan.home');

});
