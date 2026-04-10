<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function() {
    Route::get('/suppliers', function() {
        return view('suppliers::index');
    })->name('suppliers.index');
});
