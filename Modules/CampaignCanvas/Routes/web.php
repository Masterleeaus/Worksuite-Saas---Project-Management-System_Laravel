<?php

use Illuminate\Support\Facades\Route;
use Modules\CampaignCanvas\Http\Controllers\GalleryController;
use Modules\CampaignCanvas\Http\Controllers\EditorController;
use Modules\CampaignCanvas\Http\Controllers\DocumentController;
use Modules\CampaignCanvas\Http\Controllers\ImageUploadController;

Route::middleware(['web', 'auth', 'account'])
    ->prefix('account/campaign-canvas')
    ->as('campaigncanvas.')
    ->group(function () {

    // Gallery
    Route::get('/', [GalleryController::class, 'index'])->name('gallery.index');

    // Editor
    Route::get('/editor/new', [EditorController::class, 'create'])->name('editor.create');
    Route::get('/editor/{uuid}', [EditorController::class, 'edit'])->name('editor.edit');

    // Document CRUD (JSON API endpoints called by the JS editor)
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::put('/documents/{uuid}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{uuid}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::post('/documents/{uuid}/duplicate', [DocumentController::class, 'duplicate'])->name('documents.duplicate');
    Route::patch('/documents/{uuid}/rename', [DocumentController::class, 'rename'])->name('documents.rename');

    // Image upload
    Route::post('/upload-image', [ImageUploadController::class, 'store'])->name('upload.image');
});
