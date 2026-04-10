<?php

namespace Modules\BookingModule\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Appointment';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Appointment Dashboard'),
            'icon' => '',
            'name' => 'appointment-dashboard',
            'parent' => 'dashboard',
            'order' => 150,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'appointment.dashboard',
            'module' => $module,
            'permission' => 'appointment dashboard manage'
        ]);
        $menu->add([
            'title' => __('Appointment'),
            'icon' => 'calendar-time',
            'name' => 'appointment',
            'parent' => null,
            'order' => 1000,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'appointment manage'
        ]);
        $menu->add([
            'title' => __('Appointments'),
            'icon' => '',
            'name' => 'appointments',
            'parent' => 'appointment',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'appointments.index',
            'module' => $module,
            'permission' => 'appointments manage'
        ]);
        $menu->add([
            'title' => __('Questions'),
            'icon' => '',
            'name' => 'questions',
            'parent' => 'appointment',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'questions.index',
            'module' => $module,
            'permission' => 'question manage'
        ]);
        $menu->add([
            'title' => __('Schedule'),
            'icon' => '',
            'name' => 'schedule',
            'parent' => 'appointment',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'schedules.index',
            'module' => $module,
            'permission' => 'schedule manage'
        ]);

                $menu->add([
            'title' => __('Unassigned Bookings'),
            'icon' => '',
            'name' => 'schedule-unassigned',
            'parent' => 'appointment',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'schedules.unassigned',
            'module' => $module,
            'permission' => 'schedule show'
        ]);

        $menu->add([
            'title' => __('My Bookings'),
            'icon' => '',
            'name' => 'schedule-mine',
            'parent' => 'appointment',
            'order' => 26,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'schedules.mine',
            'module' => $module,
            'permission' => 'schedule show'
        ]);

        $menu->add([
            'title' => __('Dispatch Board'),
            'icon' => '',
            'name' => 'appointment-dispatch',
            'parent' => 'appointment',
            'order' => 27,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'appointment.dispatch',
            'module' => $module,
            'permission' => 'appointment dispatch'
        ]);

        $menu->add([
            'title' => __('Settings'),
            'icon' => '',
            'name' => 'appointment-settings',
            'parent' => 'appointment',
            'order' => 90,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'appointment.settings.auto_assign',
            'module' => $module,
            'permission' => 'appointment settings manage'
        ]);

        

$menu->add([
    'title' => __('Notification preferences'),
    'icon' => '',
    'name' => 'appointment-notification-preferences',
    'parent' => 'appointment-settings',
    'order' => 91,
    'ignore_if' => [],
    'depend_on' => [],
    'route' => 'appointment.settings.notification_preferences',
    'module' => $module,
    'permission' => 'appointment settings manage'
]);

$menu->add([
            'title' => __('Staff Capacity'),
            'icon' => '',
            'name' => 'appointment-staff-capacity',
            'parent' => 'appointment',
            'order' => 91,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'appointment.settings.staff_capacity',
            'module' => $module,
            'permission' => 'appointment settings manage'
        ]);
    }
}
