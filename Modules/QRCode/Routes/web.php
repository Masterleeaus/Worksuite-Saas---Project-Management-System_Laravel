<?php



use Illuminate\Support\Facades\Route;
use Modules\QRCode\Http\Controllers\QRCodeController;
use Modules\QRCode\Http\Controllers\QrScanLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public scan endpoint — no auth required so that a scanned QR code resolves for anyone
Route::get('qr/scan/{id}', [QRCodeController::class, 'scan'])->name('qrcode.scan');

// Admin routes
Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {
    Route::group(
        ['prefix' => 'qrcode', 'as' => 'qrcode.'],
        function () {
            Route::get('/download/{id}/{format}', [QRCodeController::class, 'download'])->name('download');
            Route::get('fields/{type}', [QRCodeController::class, 'fields'])->name('fields');
            Route::post('preview', [QRCodeController::class, 'preview'])->name('preview');
            Route::get('{id}/scan-logs', [QrScanLogController::class, 'index'])->name('scan-logs');
        }
    );

    Route::resource('qrcode', QRCodeController::class)->except(['update']);
});
