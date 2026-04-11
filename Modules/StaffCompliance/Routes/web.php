<?php

use Illuminate\Support\Facades\Route;
use Modules\StaffCompliance\Http\Controllers\Web\Admin\ComplianceDashboardController;
use Modules\StaffCompliance\Http\Controllers\Web\Admin\DocumentTypeController;
use Modules\StaffCompliance\Http\Controllers\Web\Admin\WorkerComplianceDocumentController;

Route::middleware(['web', 'auth'])->prefix('account')->group(function () {

    // Compliance Dashboard
    Route::get('compliance', [ComplianceDashboardController::class, 'index'])->name('compliance.dashboard');

    // Document Types (admin management)
    Route::prefix('compliance/types')->as('compliance.types.')->group(function () {
        Route::get('/', [DocumentTypeController::class, 'index'])->name('index');
        Route::post('/', [DocumentTypeController::class, 'store'])->name('store');
        Route::put('{id}', [DocumentTypeController::class, 'update'])->name('update');
        Route::delete('{id}', [DocumentTypeController::class, 'destroy'])->name('destroy');
    });

    // Worker Compliance Documents
    Route::prefix('compliance/documents')->as('compliance.documents.')->group(function () {
        Route::get('/', [WorkerComplianceDocumentController::class, 'index'])->name('index');
        Route::get('my-documents', [WorkerComplianceDocumentController::class, 'myDocuments'])->name('my');
        Route::post('/', [WorkerComplianceDocumentController::class, 'store'])->name('store');
        Route::get('{id}', [WorkerComplianceDocumentController::class, 'show'])->name('show');
        Route::post('{id}/verify', [WorkerComplianceDocumentController::class, 'verify'])->name('verify');
        Route::post('{id}/reject', [WorkerComplianceDocumentController::class, 'reject'])->name('reject');
    });
});
