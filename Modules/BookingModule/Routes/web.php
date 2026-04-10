<?php

use Illuminate\Support\Facades\Route;
use Modules\BookingModule\Http\Controllers\Web\Admin\BookingController;
use Modules\BookingModule\Http\Controllers\Web\Admin\BookingPageController;
use Modules\BookingModule\Http\Controllers\Web\Admin\BookingPageRequestController;
use Modules\BookingModule\Http\Controllers\Web\Admin\DispatchBoardController;
use Modules\BookingModule\Http\Controllers\Web\Provider\BookingController as ProviderBookingController;
use Modules\BookingModule\Http\Controllers\Web\Public\BookingPageController as PublicBookingPageController;
use Modules\BookingModule\Http\Controllers\Web\Public\BookingPageRequestController as PublicBookingPageRequestController;
use Modules\BookingModule\Http\Controllers\Web\Public\BookingStatusController;

Route::middleware(['web', 'auth'])->prefix('account')->group(function () {
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('dispatch-board', [DispatchBoardController::class, 'index'])->name('admin.dispatch.board');

    Route::prefix('booking')->as('admin.booking.')->group(function () {
        Route::get('dispatch-board', [DispatchBoardController::class, 'index'])->name('dispatch.board');

        Route::any('list', [BookingController::class, 'index'])->name('list');
        Route::any('list/verification', [BookingController::class, 'bookingVerificationList'])->name('list.verification');
        Route::any('list/verification/download', [BookingController::class, 'downloadBookingVerificationList'])->name('list.verification.download');
        Route::any('list/offline-payment', [BookingController::class, 'bookingOfflinePaymentList'])->name('offline.payment');
        Route::get('check', [BookingController::class, 'checkBooking'])->name('check');
        Route::get('details/{id}', [BookingController::class, 'details'])->name('details');
        Route::get('repeat-details/{id}', [BookingController::class, 'repeatDetails'])->name('repeat_details');
        Route::get('repeat-single-details/{id}', [BookingController::class, 'repeatSingleDetails'])->name('repeat_single_details');
        Route::get('status-update/{id}', [BookingController::class, 'statusUpdate'])->name('status_update');
        Route::get('up-coming-booking-cancel/{id}', [BookingController::class, 'upComingBookingCancel'])->name('up_coming_booking_cancel');
        Route::get('verification-status-update/{id}', [BookingController::class, 'verificationUpdate'])->name('verification_status_update');
        Route::post('verification-status/{id}', [BookingController::class, 'verificationStatus'])->name('verification-status');
        Route::get('payment-update/{id}', [BookingController::class, 'paymentUpdate'])->name('payment_update');
        Route::any('schedule-update/{id}', [BookingController::class, 'scheduleUpdate'])->name('schedule_update');
        Route::any('up-coming-booking-schedule-update/{id}', [BookingController::class, 'upComingBookingScheduleUpdate'])->name('up_coming_booking_schedule_update');
        Route::put('serviceman-update/{id}', [BookingController::class, 'servicemanUpdate'])->name('serviceman_update');
        Route::post('service-address-update/{id}', [BookingController::class, 'serviceAddressUpdate'])->name('service_address_update');
        Route::any('download', [BookingController::class, 'download'])->name('download');
        Route::any('invoice/{id}', [BookingController::class, 'invoice'])->name('invoice');
        Route::any('single-repeat-invoice/{id}', [BookingController::class, 'fullBookingSingleInvoice'])->name('single_invoice');
        Route::any('full-repeat-invoice/{id}', [BookingController::class, 'fullBookingInvoice'])->name('full_repeat_invoice');
        Route::any('customer-fullbooking-single-invoice/{id}/{lang}', [BookingController::class, 'customerFullBookingSingleInvoice']);
        Route::any('customer-fullbooking-invoice/{id}/{lang}', [BookingController::class, 'customerFullBookingInvoice']);
        Route::any('provider-fullbooking-single-invoice/{id}/{lang}', [BookingController::class, 'providerFullBookingSingleInvoice']);
        Route::any('provider-fullbooking-invoice/{id}/{lang}', [BookingController::class, 'providerFullBookingInvoice']);
        Route::any('serviceman-fullbooking-single-invoice/{id}/{lang}', [BookingController::class, 'servicemanFullBookingSingleInvoice']);
        Route::any('customer-invoice/{id}/{lang}', [BookingController::class, 'customerInvoice']);
        Route::any('provider-invoice/{id}/{lang}', [BookingController::class, 'providerInvoice']);
        Route::any('serviceman-invoice/{id}/{lang}', [BookingController::class, 'servicemanInvoice']);

        Route::any('switch-payment-method/{id}', [BookingController::class, 'switchPaymentMethod'])->name('switch-payment-method');
        Route::any('offline-payment/verify', [BookingController::class, 'verifyOfflinePayment'])->name('offline-payment.verify');

        Route::prefix('service')->as('service.')->group(function () {
            Route::put('update-booking-service', [BookingController::class, 'updateBookingService'])->name('update_booking_service');
            Route::put('update-repeat-booking-service', [BookingController::class, 'updateRepeatBookingService'])->name('update_repeat_booking_service');
            Route::get('ajax-get-service-info', [BookingController::class, 'ajaxGetServiceInfo'])->name('ajax-get-service-info');
            Route::get('ajax-get-variation', [BookingController::class, 'ajaxGetVariant'])->name('ajax-get-variant');
        });

        Route::get('rebooking/details/{id}', [BookingController::class, 'reBookingDetails'])->name('rebooking.details');
        Route::get('rebooking/ongoing/{id}', [BookingController::class, 'reBookingOngoing'])->name('rebooking.ongoing');
        Route::post('change-service-location/{id}', [BookingController::class, 'changeServiceLocation'])->name('change-service-location');
        Route::post('repeat-change-service-location/{id}', [BookingController::class, 'repeatChangeServiceLocation'])->name('repeat.change-service-location');

        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', [BookingPageController::class, 'index'])->name('index');
            Route::get('create', [BookingPageController::class, 'create'])->name('create');
            Route::post('/', [BookingPageController::class, 'store'])->name('store');
            Route::get('{page:slug}/edit', [BookingPageController::class, 'edit'])->name('edit');
            Route::put('{page:slug}', [BookingPageController::class, 'update'])->name('update');
            Route::delete('{page:slug}', [BookingPageController::class, 'destroy'])->name('destroy');
            Route::get('{page:slug}/preview', [BookingPageController::class, 'preview'])->name('preview');
        });

        Route::prefix('page-requests')->name('page-requests.')->group(function () {
            Route::get('/', [BookingPageRequestController::class, 'index'])->name('index');
            Route::put('{bookingPageRequest}/status', [BookingPageRequestController::class, 'updateStatus'])->name('status');
        });
    });

    Route::prefix('provider/booking')->as('provider.booking.')->group(function () {
        Route::any('list', [ProviderBookingController::class, 'index'])->name('list');
        Route::get('check', [ProviderBookingController::class, 'checkBooking'])->name('check');
        Route::get('details/{id}', [ProviderBookingController::class, 'details'])->name('details');
        Route::get('repeat-details/{id}', [ProviderBookingController::class, 'repeatDetails'])->name('repeat_details');
        Route::get('repeat-single-details/{id}', [ProviderBookingController::class, 'repeatSingleDetails'])->name('repeat_single_details');
        Route::get('request-accept/{booking_id}', [ProviderBookingController::class, 'requestAccept'])->name('accept');
        Route::get('request-ignore/{booking_id}', [ProviderBookingController::class, 'requestIgnore'])->name('ignore');
        Route::any('status-update/{id}', [ProviderBookingController::class, 'statusUpdate'])->name('status_update');
        Route::any('payment-update/{id}', [ProviderBookingController::class, 'paymentUpdate'])->name('payment_update');
        Route::any('schedule-update/{id}', [ProviderBookingController::class, 'scheduleUpdate'])->name('schedule_update');
        Route::put('serviceman-update/{id}', [ProviderBookingController::class, 'servicemanUpdate'])->name('serviceman_update');
        Route::put('service-address-update/{id}', [BookingController::class, 'serviceAddressUpdate'])->name('service_address_update');
        Route::get('up-coming-booking-cancel/{id}', [ProviderBookingController::class, 'upComingBookingCancel'])->name('up_coming_booking_cancel');
        Route::any('up-coming-booking-schedule-update/{id}', [ProviderBookingController::class, 'upComingBookingScheduleUpdate'])->name('up_coming_booking_schedule_update');
        Route::any('download', [ProviderBookingController::class, 'download'])->name('download');
        Route::any('invoice/{id}', [ProviderBookingController::class, 'invoice'])->name('invoice');
        Route::any('single-repeat-invoice/{id}', [ProviderBookingController::class, 'fullBookingSingleInvoice'])->name('single_invoice');
        Route::any('full-repeat-invoice/{id}', [ProviderBookingController::class, 'fullBookingInvoice'])->name('full_repeat_invoice');
        Route::post('evidence-photos-upload/{id}', [ProviderBookingController::class, 'evidencePhotosUpload'])->name('evidence_photos_upload');
        Route::get('otp/resend', [ProviderBookingController::class, 'resendOtp'])->name('otp.resend');
        Route::prefix('service')->as('service.')->group(function () {
            Route::put('update-booking-service', [ProviderBookingController::class, 'updateBookingService'])->name('update_booking_service');
            Route::put('update-repeat-booking-service', [ProviderBookingController::class, 'updateRepeatBookingService'])->name('update_repeat_booking_service');
            Route::get('ajax-get-service-info', [ProviderBookingController::class, 'ajaxGetServiceInfo'])->name('ajax-get-service-info');
            Route::get('ajax-get-variation', [ProviderBookingController::class, 'ajaxGetVariant'])->name('ajax-get-variant');
        });
        Route::post('change-service-location/{id}', [ProviderBookingController::class, 'changeServiceLocation'])->name('change-service-location');
        Route::post('repeat-change-service-location/{id}', [ProviderBookingController::class, 'repeatChangeServiceLocation'])->name('repeat.change-service-location');
        Route::get('calendar-view', [ProviderBookingController::class, 'calendarView'])->name('calendar.view');
        Route::get('calendar-events', [ProviderBookingController::class, 'calendarEvents'])->name('calendar.events');
        Route::get('calendar-events/bookings', [ProviderBookingController::class, 'getCalendarBookingList'])->name('calendar.events.bookings');
    });
});

Route::middleware('web')->post('/book/{slug}/request', [PublicBookingPageRequestController::class, 'store'])->name('booking.pages.request.store');
Route::middleware('web')->get('/book/{slug}', [PublicBookingPageController::class, 'show'])->name('booking.pages.show');
Route::middleware('web')->get('/booking/status', [BookingStatusController::class, 'show'])->name('booking.status.show');


use Modules\BookingModule\Http\Controllers\DashboardController as AppointmentDashboardController;
use Modules\BookingModule\Http\Controllers\AppointmentsController;
use Modules\BookingModule\Http\Controllers\QuestionController;
use Modules\BookingModule\Http\Controllers\ScheduleController;
use Modules\BookingModule\Http\Controllers\WorkloadController;
use Modules\BookingModule\Http\Controllers\ScheduleWorkloadController;
use Modules\BookingModule\Http\Controllers\SettingsController as AppointmentSettingsController;
use Modules\BookingModule\Http\Controllers\NotificationCenterController;
use Modules\BookingModule\Http\Controllers\DispatchScheduleController;
use Modules\BookingModule\Http\Controllers\PublicAppointmentController;
use Modules\BookingModule\Http\Controllers\Web\Public\ClientPortalController;

Route::group(['middleware' => ['web','auth'], 'prefix' => 'account'], function () {
    Route::get('booking-dashboard', [AppointmentDashboardController::class, 'index'])->name('appointment.dashboard');
    Route::resource('appointments', AppointmentsController::class);
    Route::get('appointments-calender', [AppointmentsController::class, 'calender'])->name('appointments.calender');
    Route::get('appointments-schedule/{id}', [AppointmentsController::class, 'scheduleshow'])->name('appointments.scheduleshow');
    Route::resource('questions', QuestionController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::post('schedules/{id}/action', [ScheduleController::class, 'action'])->name('appointment.schedules.action');
    Route::post('schedules/{id}/change-action', [ScheduleController::class, 'changeaction'])->name('appointment.schedules.changeaction');
    Route::get('appointments-unassigned', [WorkloadController::class, 'unassigned'])->name('appointments.unassigned');
    Route::get('appointments-mine', [WorkloadController::class, 'mine'])->name('appointments.mine');
    Route::get('schedules-unassigned', [ScheduleWorkloadController::class, 'unassigned'])->name('appointment.schedules.unassigned');
    Route::get('schedules-mine', [ScheduleWorkloadController::class, 'mine'])->name('appointment.schedules.mine');
    Route::post('schedules/bulk-assign', [ScheduleWorkloadController::class, 'bulkAssign'])->name('appointment.schedules.bulk_assign');
    Route::get('appointment-settings/auto-assign', [AppointmentSettingsController::class, 'autoAssign'])->name('appointment.settings.auto_assign');
    Route::post('appointment-settings/auto-assign', [AppointmentSettingsController::class, 'updateAutoAssign'])->name('appointment.settings.auto_assign.update');
    Route::get('appointment-settings/public-spam', [AppointmentSettingsController::class, 'publicSpam'])->name('appointment.settings.public_spam');
    Route::post('appointment-settings/public-spam', [AppointmentSettingsController::class, 'updatePublicSpam'])->name('appointment.settings.public_spam.update');
    Route::get('appointment-settings/notification-preferences', [AppointmentSettingsController::class, 'notificationPreferences'])->name('appointment.settings.notification_preferences');
    Route::post('appointment-settings/notification-preferences', [AppointmentSettingsController::class, 'updateNotificationPreferences'])->name('appointment.settings.notification_preferences.update');
    Route::get('appointment-settings/staff-capacity', [AppointmentSettingsController::class, 'staffCapacity'])->name('appointment.settings.staff_capacity');
    Route::post('appointment-settings/staff-capacity', [AppointmentSettingsController::class, 'updateStaffCapacity'])->name('appointment.settings.staff_capacity.update');
    Route::get('appointment-settings/legacy-import', [AppointmentSettingsController::class, 'legacyImport'])->name('appointment.settings.legacy_import');
    Route::get('dispatch', [\Modules\BookingModule\Http\Controllers\DispatchBoardController::class, 'index'])->name('appointment.dispatch');
    Route::post('dispatch/move', [\Modules\BookingModule\Http\Controllers\DispatchBoardController::class, 'move'])->name('appointment.dispatch.move');
    Route::get('dispatch/schedule/{id}/edit', [DispatchScheduleController::class, 'edit'])->name('appointment.dispatch.schedule.edit');
    Route::post('dispatch/schedule/{id}', [DispatchScheduleController::class, 'update'])->name('appointment.dispatch.schedule.update');
    Route::get('appointment/notifications', [NotificationCenterController::class, 'index'])->name('appointment.notifications');
    Route::post('appointment/notifications/{id}/read', [NotificationCenterController::class, 'markRead'])->name('appointment.notifications.read');
});

Route::group(['middleware' => ['web','throttle:appointment-public','appointment.public.honeypot'], 'prefix' => 'public/{slug}'], function () {
    Route::get('appointments/{id}', [PublicAppointmentController::class, 'create'])->name('appointments.public.create');
    Route::post('appointments-store/{id}', [PublicAppointmentController::class, 'store'])->name('appointments.public.store');
    Route::get('appointment-search', [PublicAppointmentController::class, 'appointmentSearch'])->name('appointments.public.search');
    Route::post('appointment/post/search', [PublicAppointmentController::class, 'appointmentSearch'])->name('appointments.public.search.post');
    Route::get('appointment/{id}', [PublicAppointmentController::class, 'index'])->name('appointments.public.view');
    Route::post('support-ticket/{id}', [PublicAppointmentController::class, 'CancelForm'])->name('appointments.public.cancel_form');
    Route::post('appointment-callback/{id}', [PublicAppointmentController::class, 'Callback'])->name('appointments.public.callback');
});

Route::middleware('web')->get('/portal/{slug}', [ClientPortalController::class, 'show'])->name('booking.portal.show');

// ── FSM Cleaning Bookings ────────────────────────────────────────────────────
Route::middleware(['web', 'auth'])->prefix('account')->group(function () {
    Route::prefix('cleaning-bookings')->as('cleaning-bookings.')->group(function () {
        Route::post('/', [\Modules\BookingModule\Http\Controllers\Cleaning\CleaningBookingController::class, 'store'])
             ->name('store');
        Route::patch('{booking}/status', [\Modules\BookingModule\Http\Controllers\Cleaning\CleaningBookingController::class, 'updateStatus'])
             ->name('update-status');
        Route::patch('{booking}/assign', [\Modules\BookingModule\Http\Controllers\Cleaning\CleaningBookingController::class, 'assignCleaner'])
             ->name('assign-cleaner');
    });
});
