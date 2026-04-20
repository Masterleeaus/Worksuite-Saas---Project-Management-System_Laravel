<?php
namespace Modules\BookingModule\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleReminder2hNotification extends Notification implements ShouldQueue
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
            ?? __('bookingmodule::notifications.reminder_2h_subject', [], 'en')
            ?: 'Appointment Reminder — Starting Soon';

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

        $intro = $this->data['message'] ?? 'Your appointment is starting in approximately 2 hours. Please see the details below.';

        $mail = (new MailMessage)->subject($subject)->line($intro);

        foreach ($lines as $line) {
            $mail->line($line);
        }

        return $mail;
    }
}
