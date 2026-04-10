<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\Api\InventoryApiController;

Route::get('/ping', [InventoryApiController::class, 'ping'])->name('ping');
