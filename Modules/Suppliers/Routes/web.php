<?php

use Illuminate\Support\Facades\Route;
use Modules\Suppliers\Http\Controllers\SuppliersController;

Route::prefix('suppliers')->name('suppliers.')->group(function () {
    Route::get('/', [SuppliersController::class, 'index'])
        ->middleware('permission:view_suppliers')
        ->name('index');

    Route::get('/list', [SuppliersController::class, 'list'])
        ->middleware('permission:view_suppliers')
        ->name('list');
});
