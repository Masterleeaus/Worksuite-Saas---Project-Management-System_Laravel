<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('account')->group(function () {
    Route::get('items', function () {
        return redirect()->route('items.index');
    })->name('fielditems.index');
});
