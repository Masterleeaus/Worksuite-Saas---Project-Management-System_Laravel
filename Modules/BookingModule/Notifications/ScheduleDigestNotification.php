<?php
namespace Modules\BookingModule\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $data = []) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->data['subject'] ?? 'Appointment update')
            ->line($this->data['message'] ?? 'There is an update to your appointment booking.');
    }
}
