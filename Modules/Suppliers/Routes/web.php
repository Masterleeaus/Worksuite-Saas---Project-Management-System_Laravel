<?php

use Illuminate\Support\Facades\Route;
use Modules\Suppliers\Http\Controllers\SupplierController;
use Modules\Suppliers\Http\Controllers\SupplierRatingController;

Route::prefix('account/suppliers')->name('suppliers.')->group(function () {

    // Supplier CRUD
    Route::get('/', [SupplierController::class, 'index'])->name('index');
    Route::get('/create', [SupplierController::class, 'create'])->name('create');
    Route::post('/', [SupplierController::class, 'store'])->name('store');
    Route::get('/{id}', [SupplierController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SupplierController::class, 'update'])->name('update');
    Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');

    // Supplier ratings
    Route::get('/ratings', [SupplierRatingController::class, 'index'])->name('ratings.index');
    Route::post('/{id}/rating', [SupplierRatingController::class, 'updateRating'])->name('rating.update');
});
