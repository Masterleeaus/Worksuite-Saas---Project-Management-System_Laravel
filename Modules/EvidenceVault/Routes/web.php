<?php

use Illuminate\Support\Facades\Route;
use Modules\EvidenceVault\Http\Controllers\EvidenceVaultController;

/*
|--------------------------------------------------------------------------
| Web Routes – Evidence Vault (Admin Panel)
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {

    Route::group(['prefix' => 'evidence-vault', 'as' => 'evidence-vault.'], function () {

        // Admin: list all submissions (filterable by job / date / cleaner)
        Route::get('/', [EvidenceVaultController::class, 'index'])->name('index');

        // Admin: view a single submission with photos + signature
        Route::get('{id}', [EvidenceVaultController::class, 'show'])->name('show');

        // Admin: delete a submission
        Route::delete('{id}', [EvidenceVaultController::class, 'destroy'])->name('destroy');
    });
});
