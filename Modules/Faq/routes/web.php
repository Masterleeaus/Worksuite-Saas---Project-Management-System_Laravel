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
            Route::view('/faq', 'faq::faq.faq')
                ->name('admin.faq')
                ->middleware(['admin.auth', 'permission']);
        });
    });
