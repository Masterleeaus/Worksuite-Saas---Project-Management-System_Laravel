<?php

use Illuminate\Support\Facades\Route;
use Modules\Blogs\Http\Controllers\BlogsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth'])
    ->prefix('account')
    ->group(function () {
        Route::prefix('admin/content')->group(function () {
            Route::get('/blogs', [BlogsController::class, 'blogPost'])
                ->name('admin.blogs')
                ->middleware(['admin.auth', 'permission']);
        });
    });

Route::middleware('web')->group(function () {
    Route::get('/blogs', [BlogsController::class, 'blogList'])->name('blogs.list');
    Route::get('/blogs/{slug}', [BlogsController::class, 'blogDetails'])->name('blogs.details');
});
