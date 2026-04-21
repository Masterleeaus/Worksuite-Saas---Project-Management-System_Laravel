<?php

use Illuminate\Support\Facades\Route;
use Modules\Accountings\Http\Controllers\Api\AccountingEngineController;

Route::group(['middleware' => ['api', 'auth'], 'prefix' => 'v1/accountings'], function () {
    Route::post('visits/{visitRef}/cost', [AccountingEngineController::class, 'postVisitCost'])->name('api.v1.accountings.visits.cost');
    Route::post('invoices/{invoiceId}/ledger', [AccountingEngineController::class, 'postInvoiceToLedger'])->name('api.v1.accountings.invoices.ledger');
    Route::post('invoices/{invoiceId}/adjust', [AccountingEngineController::class, 'adjustInvoice'])->name('api.v1.accountings.invoices.adjust');
    Route::post('invoices/{invoiceId}/writeoff', [AccountingEngineController::class, 'writeoffInvoice'])->name('api.v1.accountings.invoices.writeoff');
    Route::post('payments/{paymentId}/record', [AccountingEngineController::class, 'recordPayment'])->name('api.v1.accountings.payments.record');
    Route::post('periods/close', [AccountingEngineController::class, 'closePeriod'])->name('api.v1.accountings.periods.close');
    Route::get('profitability/site/{siteRef}', [AccountingEngineController::class, 'siteProfitability'])->name('api.v1.accountings.profitability.site');
    Route::get('profitability/contract/{contractRef}', [AccountingEngineController::class, 'contractProfitability'])->name('api.v1.accountings.profitability.contract');
    Route::get('transactions', [AccountingEngineController::class, 'transactions'])->name('api.v1.accountings.transactions.index');
});
