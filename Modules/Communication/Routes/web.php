<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth'])
    ->prefix('account')
    ->group(function () {
        Route::prefix('admin/content')->group(function () {
            Route::view('/communication', 'communication::communication.communication')
                ->name('admin.communication')
                ->middleware(['admin.auth', 'permission']);
        });
    });
