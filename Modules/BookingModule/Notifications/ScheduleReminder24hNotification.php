<?php
namespace Modules\BookingModule\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleReminder24hNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $data = []) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $subject = $this->data['subject']
            ?? __('bookingmodule::notifications.reminder_24h_subject', [], 'en')
            ?: 'Appointment Reminder — Tomorrow';

        $lines = [];

        if (!empty($this->data['date'])) {
            $lines[] = __('Date') . ': ' . $this->data['date'];
        }

        if (!empty($this->data['start_time'])) {
            $lines[] = __('Time') . ': ' . $this->data['start_time'];
        }

        if (!empty($this->data['location'])) {
            $lines[] = __('Location') . ': ' . $this->data['location'];
        }

        $intro = $this->data['message'] ?? 'You have an appointment scheduled for tomorrow. Please see the details below.';

        $mail = (new MailMessage)->subject($subject)->line($intro);

        foreach ($lines as $line) {
            $mail->line($line);
        }

        return $mail;
    }
}
