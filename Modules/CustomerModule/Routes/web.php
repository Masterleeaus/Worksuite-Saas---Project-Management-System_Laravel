<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerModule\Http\Controllers\Web\Admin\LoyaltyPointController;
use Modules\CustomerModule\Http\Controllers\Web\Admin\WalletController;
use Modules\CustomerModule\Http\Controllers\Web\Admin\SubscribeNewsletterController;
use Modules\CustomerModule\Http\Controllers\Web\Admin\CustomerController;
use Modules\CustomerModule\Http\Controllers\PagesController;
use Modules\CustomerModule\Http\Controllers\Client\CleaningInfoController;
use Modules\CustomerModule\Http\Controllers\Client\ClientAddressController;
use Modules\CustomerModule\Http\Controllers\Client\FSMBookingsController;

Route::middleware(['web', 'auth'])->prefix('account')->group(function () {
    Route::get('about-us', [PagesController::class, 'aboutUs'])->name('about-us');
    Route::get('privacy-policy', [PagesController::class, 'privacyPolicy'])->name('privacy-policy');
    Route::get('terms-and-conditions', [PagesController::class, 'termsAndConditions'])->name('terms-and-conditions');
    Route::get('refund-policy', [PagesController::class, 'refundPolicy'])->name('refund-policy');
    Route::get('return-policy', [PagesController::class, 'returnPolicy'])->name('return-policy');
    Route::get('cancellation-policy', [PagesController::class, 'cancellationPolicy'])->name('cancellation-policy');

    // -----------------------------------------------------------------------
    // FSM Client Overlay — cleaning-info, properties and booking-history tabs
    // These are AJAX tab routes that extend the core clients.show page.
    // -----------------------------------------------------------------------
    Route::group(['prefix' => 'clients', 'as' => 'fsm.clients.'], function () {
        // "Cleaning Info" tab
        Route::get('{id}/cleaning-info', [CleaningInfoController::class, 'show'])->name('cleaning-info');
        Route::post('{id}/cleaning-info', [CleaningInfoController::class, 'update'])->name('cleaning-info.update');

        // "Properties" tab
        Route::get('{clientId}/properties', [ClientAddressController::class, 'index'])->name('properties');
        Route::post('{clientId}/properties', [ClientAddressController::class, 'store'])->name('properties.store');
        Route::put('{clientId}/properties/{addressId}', [ClientAddressController::class, 'update'])->name('properties.update');
        Route::delete('{clientId}/properties/{addressId}', [ClientAddressController::class, 'destroy'])->name('properties.destroy');

        // "Booking History" tab
        Route::get('{id}/fsm-bookings', [FSMBookingsController::class, 'show'])->name('fsm-bookings');
    });

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin', 'actch:admin_panel']], function () {
        Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
            Route::any('list', [CustomerController::class, 'index'])->name('index');
            Route::any('create', [CustomerController::class, 'create'])->name('create');
            Route::post('store', [CustomerController::class, 'store'])->name('store');
            Route::any('detail/{id}', [CustomerController::class, 'show'])->name('detail');
            Route::get('edit/{id}', [CustomerController::class, 'edit'])->name('edit');
            Route::put('update/{id}', [CustomerController::class, 'update'])->name('update');
            Route::any('status-update/{id}', [CustomerController::class, 'statusUpdate'])->name('status-update');
            Route::delete('delete/{id}', [CustomerController::class, 'destroy'])->name('delete');
            Route::any('download', [CustomerController::class, 'download'])->name('download');

            Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
                Route::get('add-fund', [WalletController::class, 'addFund'])->name('add-fund');
                Route::post('add-fund', [WalletController::class, 'storeFund']);
                Route::any('report', [WalletController::class, 'getFuncReport'])->name('report');
                Route::any('report/download', [WalletController::class, 'getFuncReportDownload'])->name('report.download');
            });

            Route::group(['prefix' => 'loyalty-point', 'as' => 'loyalty-point.'], function () {
                Route::any('report', [LoyaltyPointController::class, 'getLoyaltyPointReport'])->name('report');
                Route::any('report/download', [LoyaltyPointController::class, 'getLoyaltyPointReportDownload'])->name('report.download');
            });

            Route::group(['prefix' => 'newsletter', 'as' => 'newsletter.'], function () {
                Route::get('list', [SubscribeNewsletterController::class, 'index'])->name('index');
                Route::any('download', [SubscribeNewsletterController::class, 'download'])->name('download');
            });
        });
    });
});

