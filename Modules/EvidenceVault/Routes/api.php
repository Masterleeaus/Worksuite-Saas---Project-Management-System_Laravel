<?php

use Illuminate\Support\Facades\Route;
use Modules\EvidenceVault\Http\Controllers\Api\EvidenceVaultApiController;

/*
|--------------------------------------------------------------------------
| API Routes – Evidence Vault (PWA / Mobile)
|--------------------------------------------------------------------------
|
| These routes are stateless and are protected by the API auth guard.
| They are used by the field-worker PWA to submit evidence on job completion.
|
*/

Route::middleware('auth:sanctum')->group(function () {

    // Submit evidence (photos + optional signature) for a job.
    Route::post('evidence-vault/submit', [EvidenceVaultApiController::class, 'submit'])
        ->name('api.evidence-vault.submit');

    // Retrieve a single submission summary.
    Route::get('evidence-vault/{id}', [EvidenceVaultApiController::class, 'show'])
        ->name('api.evidence-vault.show');
});
