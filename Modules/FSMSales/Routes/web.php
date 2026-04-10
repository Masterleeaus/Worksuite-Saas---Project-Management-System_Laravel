<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMSales\Http\Controllers\InvoiceController;
use Modules\FSMSales\Http\Controllers\UnbilledOrdersController;
use Modules\FSMSales\Http\Controllers\RecurringInvoiceController;
use Modules\FSMSales\Http\Controllers\BulkInvoiceController;
use Modules\FSMSales\Http\Controllers\DashboardController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/sales')
    ->group(function () {

        // ── Revenue Dashboard ────────────────────────────────────────────────
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('fsmsales.dashboard');

        // ── Client Invoices ──────────────────────────────────────────────────
        Route::get('/invoices',                    [InvoiceController::class, 'index'])->name('fsmsales.invoices.index');
        Route::get('/invoices/create',             [InvoiceController::class, 'create'])->name('fsmsales.invoices.create');
        Route::post('/invoices',                   [InvoiceController::class, 'store'])->name('fsmsales.invoices.store');
        Route::get('/invoices/{id}',               [InvoiceController::class, 'show'])->name('fsmsales.invoices.show');
        Route::get('/invoices/{id}/edit',          [InvoiceController::class, 'edit'])->name('fsmsales.invoices.edit');
        Route::post('/invoices/{id}',              [InvoiceController::class, 'update'])->name('fsmsales.invoices.update');
        Route::post('/invoices/{id}/delete',       [InvoiceController::class, 'destroy'])->name('fsmsales.invoices.destroy');

        // Line management
        Route::post('/invoices/{id}/lines',                  [InvoiceController::class, 'addLine'])->name('fsmsales.invoices.lines.add');
        Route::post('/invoices/{id}/lines/{lineId}/delete',  [InvoiceController::class, 'deleteLine'])->name('fsmsales.invoices.lines.delete');

        // Quick-create invoice from a single FSM Order
        Route::post('/invoices/from-order/{orderId}', [InvoiceController::class, 'createFromOrder'])->name('fsmsales.invoices.from-order');

        // ── Bulk Invoice Creation ────────────────────────────────────────────
        Route::get('/bulk-invoice',  [BulkInvoiceController::class, 'create'])->name('fsmsales.bulk.create');
        Route::post('/bulk-invoice', [BulkInvoiceController::class, 'store'])->name('fsmsales.bulk.store');

        // ── Unbilled Orders Report ───────────────────────────────────────────
        Route::get('/unbilled', [UnbilledOrdersController::class, 'index'])->name('fsmsales.unbilled.index');

        // ── Recurring Invoices ───────────────────────────────────────────────
        Route::get('/recurring',                          [RecurringInvoiceController::class, 'index'])->name('fsmsales.recurring.index');
        Route::get('/recurring/{id}',                     [RecurringInvoiceController::class, 'show'])->name('fsmsales.recurring.show');
        Route::post('/recurring/{id}/convert',            [RecurringInvoiceController::class, 'convertToInvoice'])->name('fsmsales.recurring.convert');
        Route::post('/recurring/{id}/mark-paid',          [RecurringInvoiceController::class, 'markPaid'])->name('fsmsales.recurring.mark-paid');
        Route::post('/recurring/{id}/delete',             [RecurringInvoiceController::class, 'destroy'])->name('fsmsales.recurring.destroy');
    });
