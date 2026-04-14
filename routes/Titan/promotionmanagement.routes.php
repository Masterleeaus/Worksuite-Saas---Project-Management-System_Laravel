<?php

use Illuminate\Support\Facades\Route;

if (!class_exists(\Modules\PromotionManagement\Providers\PromotionManagementServiceProvider::class)) {
    return;
}

Route::middleware(['web', 'auth'])
    ->prefix('titan/promotionmanagement')
    ->name('titan.promotionmanagement.')
    ->group(function () {
        Route::get('/', function () {
            if (Route::has('admin.discount.list')) {
                return redirect()->route('admin.discount.list');
            }

            abort(404);
        })->name('index');

        Route::get('/ads', function () {
            if (Route::has('admin.advertisements.ads-list')) {
                return redirect()->route('admin.advertisements.ads-list');
            }

            abort(404);
        })->name('ads');
    });
