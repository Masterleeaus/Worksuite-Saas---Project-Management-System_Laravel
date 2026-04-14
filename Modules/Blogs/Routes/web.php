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
            Route::view('/blogs', 'blogs::blogs.blogs')
                ->name('admin.blogs')
                ->middleware(['admin.auth', 'permission']);
        });
    });
