<?php

namespace Modules\BookingModule\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\BookingModule\Entities\Appointment;

class AppointmentAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('bookingmodule::assignment.mail.assigned_subject'))
            ->line(__('bookingmodule::assignment.mail.assigned_line', ['name' => $this->appointment->name ?? __('bookingmodule::assignment.labels.appointment')]))
            ->action(__('bookingmodule::assignment.mail.view'), url('/appointments/' . $this->appointment->id));
    }

    public function toArray($notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'name' => $this->appointment->name,
            'type' => 'appointment_assigned',
        ];
    }
}
