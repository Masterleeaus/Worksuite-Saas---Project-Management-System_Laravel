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
            Route::view('/testimonials', 'testimonials::testimonials.testimonials')
                ->name('admin.testimonials')
                ->middleware(['admin.auth', 'permission']);
        });
    });
