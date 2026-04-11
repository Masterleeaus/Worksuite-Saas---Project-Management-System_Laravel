<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;
use Modules\Inventory\Http\Controllers\ItemController;
use Modules\Inventory\Http\Controllers\WarehouseController;
use Modules\Inventory\Http\Controllers\MovementController;
use Modules\Inventory\Http\Controllers\SupplierController;
use Modules\Inventory\Http\Controllers\PurchaseController;
use Modules\Inventory\Http\Controllers\TransferController;

Route::get('/', [InventoryController::class, 'index'])->name('index');

Route::middleware('permission:inventory.view')->group(function () {
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
    Route::get('/movements', [MovementController::class, 'index'])->name('movements.index');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index')
        ->middleware('permission:inventory.suppliers.view');
    Route::get('/purchasing', [PurchaseController::class, 'index'])->name('purchasing.index')
        ->middleware('permission:inventory.purchasing.view');
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index')
        ->middleware('permission:inventory.transfer.view');
});

Route::middleware('permission:inventory.manage')->group(function () {
    // Items
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Warehouses
    Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
    Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
    Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
    Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
    Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');

    // Movements
    Route::post('/movements', [MovementController::class, 'store'])->name('movements.store');
    Route::delete('/movements/{movement}', [MovementController::class, 'destroy'])->name('movements.destroy');

    // Suppliers
    Route::middleware('permission:inventory.suppliers.manage')->group(function(){
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });

    // Purchasing
    Route::middleware('permission:inventory.purchasing.manage')->group(function(){
        Route::get('/purchasing/create', [PurchaseController::class, 'create'])->name('purchasing.create');
        Route::post('/purchasing', [PurchaseController::class, 'store'])->name('purchasing.store');
        Route::get('/purchasing/{order}/receive', [PurchaseController::class, 'receiveForm'])->name('purchasing.receive.form');
        Route::post('/purchasing/{order}/receive', [PurchaseController::class, 'receive'])->name('purchasing.receive');
    });

    // Transfers
    Route::middleware('permission:inventory.transfer.manage')->group(function(){
        Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create');
        Route::post('/transfers', [TransferController::class, 'store'])->name('transfers.store');
        Route::post('/transfers/{transfer}/dispatch', [TransferController::class, 'dispatch'])->name('transfers.dispatch');
        Route::post('/transfers/{transfer}/receive', [TransferController::class, 'receive'])->name('transfers.receive');
        Route::post('/transfers/{transfer}/approve', [TransferController::class, 'approve'])->name('transfers.approve');
        Route::delete('/transfers/{transfer}', [TransferController::class, 'destroy'])->name('transfers.destroy');
    });
});
