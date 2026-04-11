<?php

use Illuminate\Support\Facades\Route;
use Modules\Security\Http\Controllers\SecurityController;
use Modules\Security\Http\Controllers\AccessCardController;
use Modules\Security\Http\Controllers\InOutPermitController;
use Modules\Security\Http\Controllers\WorkPermitController;
use Modules\Security\Http\Controllers\PackageController;
use Modules\Security\Http\Controllers\ParkingController;
use Modules\Security\Http\Controllers\NoteController;
use Modules\Security\Http\Controllers\AccessLogController;

Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {
    
    // DASHBOARD & UNIFIED VIEWS
    Route::get('security/dashboard', [SecurityController::class, 'dashboard'])->name('security.dashboard');
    Route::get('security/audit-trail', [SecurityController::class, 'auditTrail'])->name('security.audit_trail');
    Route::get('security/approvals', [SecurityController::class, 'approvalsQueue'])->name('security.approvals');

    // ACCESS CARDS
    Route::get('security/access-cards/download/{id}', [AccessCardController::class, 'download'])->name('security.access_cards.download');
    Route::get('security/access-cards/export', [AccessCardController::class, 'export'])->name('security.access_cards.export');
    Route::post('security/access-cards/quick-action', [AccessCardController::class, 'applyQuickAction'])->name('security.access_cards.quick_action');
    Route::resource('security/access-cards', AccessCardController::class)->names('security.access-cards');

    // IN/OUT PERMITS
    Route::get('security/inout-permits/download/{id}', [InOutPermitController::class, 'download'])->name('security.inout_permits.download');
    Route::get('security/inout-permits/export', [InOutPermitController::class, 'export'])->name('security.inout_permits.export');
    Route::post('security/inout-permits/quick-action', [InOutPermitController::class, 'applyQuickAction'])->name('security.inout_permits.quick_action');
    Route::get('security/inout-permits/{id}/approve', [InOutPermitController::class, 'approve'])->name('security.inout_permits.approve');
    Route::post('security/inout-permits/{id}/process-approval', [InOutPermitController::class, 'processApproval'])->name('security.inout_permits.process_approval');
    Route::resource('security/inout-permits', InOutPermitController::class)->names('security.inout-permits');

    // WORK PERMITS
    Route::get('security/work-permits/download/{id}', [WorkPermitController::class, 'download'])->name('security.work_permits.download');
    Route::get('security/work-permits/export', [WorkPermitController::class, 'export'])->name('security.work_permits.export');
    Route::post('security/work-permits/quick-action', [WorkPermitController::class, 'applyQuickAction'])->name('security.work_permits.quick_action');
    Route::get('security/work-permits/{id}/approve', [WorkPermitController::class, 'approve'])->name('security.work_permits.approve');
    Route::post('security/work-permits/{id}/process-approval', [WorkPermitController::class, 'processApproval'])->name('security.work_permits.process_approval');
    Route::post('security/work-permits/{id}/upload-files', [WorkPermitController::class, 'uploadFiles'])->name('security.work_permits.upload_files');
    Route::resource('security/work-permits', WorkPermitController::class)->names('security.work-permits');

    // PACKAGES
    Route::get('security/packages/download/{id}', [PackageController::class, 'download'])->name('security.packages.download');
    Route::get('security/packages/export', [PackageController::class, 'export'])->name('security.packages.export');
    Route::post('security/packages/quick-action', [PackageController::class, 'applyQuickAction'])->name('security.packages.quick_action');
    Route::post('security/packages/{id}/mark-received', [PackageController::class, 'markReceived'])->name('security.packages.mark_received');
    Route::resource('security/packages', PackageController::class)->names('security.packages');

    // PARKING
    Route::get('security/parking/download/{id}', [ParkingController::class, 'download'])->name('security.parking.download');
    Route::get('security/parking/export', [ParkingController::class, 'export'])->name('security.parking.export');
    Route::post('security/parking/quick-action', [ParkingController::class, 'applyQuickAction'])->name('security.parking.quick_action');
    Route::resource('security/parking', ParkingController::class)->names('security.parking');

    // NOTES
    Route::get('security/notes/export', [NoteController::class, 'export'])->name('security.notes.export');
    Route::post('security/notes/quick-action', [NoteController::class, 'applyQuickAction'])->name('security.notes.quick_action');
    Route::resource('security/notes', NoteController::class)->names('security.notes');

    // ACCESS LOGS
    Route::get('security/access-logs', [AccessLogController::class, 'index'])->name('security.access_logs.index');
    Route::get('security/access-logs/data', [AccessLogController::class, 'data'])->name('security.access_logs.data');
    Route::get('security/access-logs/{id}', [AccessLogController::class, 'show'])->name('security.access_logs.show');
    Route::get('security/access-logs/denied/attempts', [AccessLogController::class, 'deniedAttempts'])->name('security.access_logs.denied_attempts');
    Route::post('security/access-logs/trail', [AccessLogController::class, 'trail'])->name('security.access_logs.trail');
    Route::get('security/access-logs/summary', [AccessLogController::class, 'summary'])->name('security.access_logs.summary');
    Route::get('security/access-logs/export', [AccessLogController::class, 'export'])->name('security.access_logs.export');
    Route::post('security/access-logs/cleanup', [AccessLogController::class, 'cleanup'])->name('security.access_logs.cleanup');
});
