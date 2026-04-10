<?php

use Illuminate\Support\Facades\Route;
use Modules\Communication\app\Http\Controllers\InboxController;
use Modules\Communication\app\Http\Controllers\TemplateController;
use Modules\Communication\app\Http\Controllers\BulkSendController;
use Modules\Communication\app\Http\Controllers\AutomationController;

/*
|--------------------------------------------------------------------------
| Communication Module – Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth'])
    ->prefix('communications')
    ->name('communications.')
    ->group(function () {

        // Unified inbox
        Route::get('/', [InboxController::class, 'index'])->name('index');

        // Compose & send (must be before the /{communication} wildcard)
        Route::get('/compose', [InboxController::class, 'compose'])->name('compose');
        Route::post('/send', [InboxController::class, 'send'])->name('send');

        // Message history (before wildcard)
        Route::get('/history', [InboxController::class, 'history'])->name('history');
        Route::get('/history/{customerId}', [InboxController::class, 'customerHistory'])->name('history.customer');

        // Bulk message send (before wildcard)
        Route::get('/bulk', [BulkSendController::class, 'index'])->name('bulk');
        Route::post('/bulk/send', [BulkSendController::class, 'send'])->name('bulk.send');

        // Communication templates CRUD
        Route::prefix('templates')->name('templates.')->group(function () {
            Route::get('/', [TemplateController::class, 'index'])->name('index');
            Route::get('/create', [TemplateController::class, 'create'])->name('create');
            Route::post('/', [TemplateController::class, 'store'])->name('store');
            Route::get('/{template}/edit', [TemplateController::class, 'edit'])->name('edit');
            Route::put('/{template}', [TemplateController::class, 'update'])->name('update');
            Route::delete('/{template}', [TemplateController::class, 'destroy'])->name('destroy');
        });

        // Automation rules management
        Route::prefix('automations')->name('automations.')->group(function () {
            Route::get('/', [AutomationController::class, 'index'])->name('index');
            Route::get('/create', [AutomationController::class, 'create'])->name('create');
            Route::post('/', [AutomationController::class, 'store'])->name('store');
            Route::get('/{automation}/edit', [AutomationController::class, 'edit'])->name('edit');
            Route::put('/{automation}', [AutomationController::class, 'update'])->name('update');
            Route::delete('/{automation}', [AutomationController::class, 'destroy'])->name('destroy');
        });

        // Single communication detail — wildcard last to avoid masking named routes
        Route::get('/{communication}', [InboxController::class, 'show'])->name('show');
    });
