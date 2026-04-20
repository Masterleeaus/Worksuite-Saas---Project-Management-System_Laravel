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
        || user()->permission('appointment manage') != 'none'
        || user()->permission('appointments manage') != 'none'
        || user()->permission('schedule manage') != 'none'
        || user()->permission('appointment dispatch') != 'none'
    );
@endphp

@if ($bookingModuleEnabled && $canViewBookingMenu)
    <x-menu-item icon="calendar-check" :text="__('Booking & Dispatch')" :addon="App::environment('demo')">
        <x-slot name="iconPath">
            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
            <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
        </x-slot>

        <div class="accordionItemContent pb-2">
            {{-- Classic booking list --}}
            @if (Route::has('bookings.index') && ($isAdminRole || user()->permission('view_booking') != 'none'))
                <x-sub-menu-item :link="route('bookings.index')" :text="__('Bookings')" />
            @endif

            {{-- Appointment/scheduling sub-items (enabled for all roles when module is active) --}}
            @if ($hasBookingUserModule)
                @if (Route::has('appointment.dashboard') && ($isAdminRole || user()->permission('appointment dashboard manage') != 'none'))
                    <x-sub-menu-item :link="route('appointment.dashboard')" :text="__('Booking Dashboard')" />
                @endif
                @if (Route::has('appointments.index') && ($isAdminRole || user()->permission('appointments manage') != 'none'))
                    <x-sub-menu-item :link="route('appointments.index')" :text="__('Appointments')" />
                @endif
                @if (Route::has('schedules.index') && ($isAdminRole || user()->permission('schedule manage') != 'none'))
                    <x-sub-menu-item :link="route('schedules.index')" :text="__('Schedules')" />
                @endif
                @if (Route::has('appointment.schedules.unassigned') && ($isAdminRole || user()->permission('schedule show') != 'none'))
                    <x-sub-menu-item :link="route('appointment.schedules.unassigned')" :text="__('Unassigned Bookings')" />
                @endif
                @if (Route::has('appointment.schedules.mine') && ($isAdminRole || user()->permission('schedule show') != 'none'))
                    <x-sub-menu-item :link="route('appointment.schedules.mine')" :text="__('My Bookings')" />
                @endif
            @endif

            {{-- Dispatch board (classic booking) --}}
            @if (Route::has('admin.dispatch.board') && ($isAdminRole || user()->permission('manage_dispatch_board') != 'none' || user()->permission('view_booking') != 'none'))
                <x-sub-menu-item :link="route('admin.dispatch.board')" :text="__('Dispatch Board')" />
            @endif

            {{-- Appointment dispatch --}}
            @if ($hasBookingUserModule && Route::has('appointment.dispatch') && ($isAdminRole || user()->permission('appointment dispatch') != 'none'))
                <x-sub-menu-item :link="route('appointment.dispatch')" :text="__('Appointment Dispatch')" />
            @endif

            {{-- Booking pages & requests --}}
            @if (Route::has('admin.booking.pages.index') && ($isAdminRole || user()->permission('view_booking_pages') != 'none' || user()->permission('manage_booking_pages') != 'none'))
                <x-sub-menu-item :link="route('admin.booking.pages.index')" :text="__('Booking Pages')" />
            @endif
            @if (Route::has('admin.booking.page-requests.index') && ($isAdminRole || user()->permission('view_booking_page_requests') != 'none'))
                <x-sub-menu-item :link="route('admin.booking.page-requests.index')" :text="__('Page Requests')" />
            @endif

            {{-- Settings (staff capacity / availability & notification preferences) --}}
            @if ($hasBookingUserModule && ($isAdminRole || user()->permission('appointment settings manage') != 'none'))
                @if (Route::has('appointment.settings.staff_capacity'))
                    <x-sub-menu-item :link="route('appointment.settings.staff_capacity')" :text="__('Availability')" />
                @endif
                @if (Route::has('appointment.settings.notification_preferences'))
                    <x-sub-menu-item :link="route('appointment.settings.notification_preferences')" :text="__('Reminders')" />
                @endif
            @endif
        </div>
    </x-menu-item>
@endif
