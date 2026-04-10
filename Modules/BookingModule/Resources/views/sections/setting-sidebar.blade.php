@if(module_enabled('Appointment') && \Modules\BookingModule\Support\AppointmentPermission::check(user(), 'appointment settings manage'))
    <x-setting-menu-item
        :active="request()->routeIs('appointment.settings.auto_assign') || request()->routeIs('appointment.settings.auto_assign.update')"
        menu="appointment_auto_assign"
        :href="route('appointment.settings.auto_assign')"
        :text="__('Appointment Auto Assign')"
    />

    <x-setting-menu-item
        :active="request()->routeIs('appointment.settings.public_spam') || request()->routeIs('appointment.settings.public_spam.update')"
        menu="appointment_public_spam"
        :href="route('appointment.settings.public_spam')"
        :text="__('Appointment Public Booking Security')"
    />

    <x-setting-menu-item
        :active="request()->routeIs('appointment.settings.notification_preferences') || request()->routeIs('appointment.settings.notification_preferences.update')"
        menu="appointment_notification_preferences"
        :href="route('appointment.settings.notification_preferences')"
        :text="__('Appointment Notification Preferences')"
    />

    <x-setting-menu-item
        :active="request()->routeIs('appointment.settings.staff_capacity') || request()->routeIs('appointment.settings.staff_capacity.update')"
        menu="appointment_staff_capacity"
        :href="route('appointment.settings.staff_capacity')"
        :text="__('Appointment Staff Capacity')"
    />

    <x-setting-menu-item
        :active="request()->routeIs('appointment.settings.legacy_import')"
        menu="appointment_legacy_import"
        :href="route('appointment.settings.legacy_import')"
        :text="__('Appointment Legacy Import')"
    />
@endif
