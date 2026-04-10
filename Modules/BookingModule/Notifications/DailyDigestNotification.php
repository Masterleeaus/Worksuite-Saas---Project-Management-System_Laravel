<?php

namespace Modules\BookingModule\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyDigestNotification extends Notification
{
    use Queueable;

    public function __construct(public ?int $companyId = null)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('bookingmodule::digest.subject'))
            ->line(__('bookingmodule::digest.intro'))
            ->action(__('bookingmodule::digest.view_workload'), url('account/schedules-mine'))
            ->line(__('bookingmodule::digest.footer'));
    }
}
