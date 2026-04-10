@php
    $bookingModuleEnabled = function_exists('module_enabled') ? module_enabled('BookingModule') : true;
    $isAdminRole = function_exists('user_roles') && in_array('admin', user_roles());
    $hasBookingUserModule = function_exists('user_modules') ? in_array('bookingmodule', user_modules()) : true;

    $canViewBookingMenu = $bookingModuleEnabled && (
        $isAdminRole
        || $hasBookingUserModule
        || user()->permission('view_booking') != 'none'
        || user()->permission('view_booking_pages') != 'none'
        || user()->permission('view_booking_page_requests') != 'none'
        || user()->permission('manage_dispatch_board') != 'none'
        || user()->permission('add_booking') != 'none'
        || user()->permission('edit_booking') != 'none'
        || user()->permission('delete_booking') != 'none'
    );
@endphp

@if ($bookingModuleEnabled && $canViewBookingMenu)
    <x-menu-item icon="calendar-check" :text="__('Booking & Dispatch')" :addon="App::environment('demo')">
        <x-slot name="iconPath">
            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
            <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
        </x-slot>

        <div class="accordionItemContent pb-2">
            @if (Route::has('bookings.index'))
                <x-sub-menu-item :link="route('bookings.index')" :text="__('Bookings')" />
            @endif
            @if (Route::has('admin.dispatch.board') && ($isAdminRole || user()->permission('manage_dispatch_board') != 'none' || user()->permission('view_booking') != 'none'))
                <x-sub-menu-item :link="route('admin.dispatch.board')" :text="__('Dispatch Board')" />
            @endif
            @if (Route::has('admin.booking.pages.index') && ($isAdminRole || user()->permission('view_booking_pages') != 'none' || user()->permission('manage_booking_pages') != 'none'))
                <x-sub-menu-item :link="route('admin.booking.pages.index')" :text="__('Booking Pages')" />
            @endif
            @if (Route::has('admin.booking.page-requests.index') && ($isAdminRole || user()->permission('view_booking_page_requests') != 'none'))
                <x-sub-menu-item :link="route('admin.booking.page-requests.index')" :text="__('Page Requests')" />
            @endif
        </div>
    </x-menu-item>
@endif


@if (in_array('bookingmodule', user_modules()))
    <x-menu-item icon="calendar-check" :text="__('Booking Dashboard')" :link="route('appointment.dashboard')" />
    <x-menu-item icon="calendar-check" :text="__('Appointments')" :link="route('appointments.index')" />
    <x-menu-item icon="calendar-check" :text="__('Schedules')" :link="route('schedules.index')" />
    <x-menu-item icon="calendar-check" :text="__('Dispatch')" :link="route('appointment.dispatch')" />
    <x-menu-item icon="calendar-check" :text="__('Availability')" :link="route('appointment.settings.staff_capacity')" />
    <x-menu-item icon="calendar-check" :text="__('Reminders')" :link="route('appointment.settings.notification_preferences')" />
@endif
