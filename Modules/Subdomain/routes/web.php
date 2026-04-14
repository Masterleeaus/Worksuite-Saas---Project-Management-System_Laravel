<?php

use App\Http\Controllers\SuperAdmin\CompanyRegisterController;
use App\Http\Controllers\SuperAdmin\FrontendController;
use App\Http\Middleware\DisableFrontend;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Modules\Subdomain\Http\Controllers\SubdomainController;
use Modules\Subdomain\Http\Middleware\SubdomainCheck;

Route::group(['middleware' => ['web', SubdomainCheck::class, DisableFrontend::class]], function () {
    Route::get('/', [FrontendController::class, 'index'])->name('front.home');
    Route::get('/contact', [FrontendController::class, 'contact'])->name('front.contact');
    Route::post('/contact-us', [FrontendController::class, 'contactUs'])->name('front.contact-us');
    Route::get('/features', [FrontendController::class, 'feature'])->name('front.feature');
    Route::get('/pricing', [FrontendController::class, 'pricing'])->name('front.pricing');
    Route::post('check-domain', [SubdomainController::class, 'checkDomain'])->name('front.check-domain');
});

Route::group(['middleware' => ['web', SubdomainCheck::class]], function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
});

Route::group(['middleware' => [SubdomainCheck::class]], function () {
    Route::get('/super-admin-login', [AuthenticatedSessionController::class, 'create'])->middleware('guest');
    Route::post('/super-admin-login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
    Route::get('forgot-company', [SubdomainController::class, 'forgotCompany'])->name('front.forgot-company')->middleware('guest');
    Route::post('forgot-company', [SubdomainController::class, 'submitForgotCompany'])->name('front.submit-forgot-password')->middleware('guest');
    Route::get('signin', [SubdomainController::class, 'workspace'])->name('front.workspace');
    Route::get('signup', [CompanyRegisterController::class, 'index'])->name('front.signup.index');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'account'], function () {
    Route::post('notify/domain', [SubdomainController::class, 'notifyDomain'])->name('notify.domain');
});
