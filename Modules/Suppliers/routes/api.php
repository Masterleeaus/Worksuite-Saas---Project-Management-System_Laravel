<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/suppliers')->group(function() {
    Route::get('/', function() {
        return response()->json(['module' => 'suppliers', 'ok' => true]);
    });
});
