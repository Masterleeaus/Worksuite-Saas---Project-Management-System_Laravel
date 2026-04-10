@php($appointmentEnabled = module_enabled('Appointment'))

@if($appointmentEnabled && \Modules\BookingModule\Support\AppointmentPermission::check(user(), 'appointment dashboard manage'))
    <x-sub-menu-item :link="route('appointment.dashboard')" :text="__('Appointment Dashboard')" />
@endif

@if($appointmentEnabled && \Modules\BookingModule\Support\AppointmentPermission::check(user(), 'appointments manage'))
    <x-sub-menu-item :link="route('appointments.index')" :text="__('Appointments')" />
@endif

@if($appointmentEnabled && \Modules\BookingModule\Support\AppointmentPermission::check(user(), 'question manage'))
    <x-sub-menu-item :link="route('questions.index')" :text="__('Questions')" />
@endif

@if($appointmentEnabled && \Modules\BookingModule\Support\AppointmentPermission::check(user(), 'schedule manage'))
    <x-sub-menu-item :link="route('schedules.index')" :text="__('Bookings')" />
@endif

@if($appointmentEnabled && \Modules\BookingModule\Support\AppointmentPermission::check(user(), 'appointments assign'))
    <x-sub-menu-item :link="route('appointments.unassigned')" :text="__('Unassigned Appointments')" />
    <x-sub-menu-item :link="route('appointment.schedules.unassigned')" :text="__('Unassigned Schedules')" />
@endif

@if($appointmentEnabled && \Modules\BookingModule\Support\AppointmentPermission::check(user(), 'appointment manage'))
    <x-sub-menu-item :link="route('appointments.mine')" :text="__('My Appointments')" />
    <x-sub-menu-item :link="route('appointment.schedules.mine')" :text="__('My Schedules')" />
@endif

@if($appointmentEnabled && \Modules\BookingModule\Support\AppointmentPermission::check(user(), 'appointment dispatch'))
    <x-sub-menu-item :link="route('appointment.dispatch')" :text="__('Dispatch Board')" />
@endif

@if($appointmentEnabled && \Modules\BookingModule\Support\AppointmentPermission::check(user(), 'appointment view notifications'))
    <x-sub-menu-item :link="route('appointment.notifications')" :text="__('Appointment Notifications')" />
@endif
