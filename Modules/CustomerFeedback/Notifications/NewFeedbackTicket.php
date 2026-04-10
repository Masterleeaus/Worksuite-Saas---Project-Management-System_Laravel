<?php

namespace Modules\CustomerFeedback\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\CustomerFeedback\Entities\FeedbackTicket;

/**
 * Notification sent to an agent when a new feedback ticket is assigned.
 */
class NewFeedbackTicket extends Notification
{
    use Queueable;

    public function __construct(public readonly FeedbackTicket $ticket) {}

    public function via(mixed $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('customer-feedback::notifications.newTicketSubject'))
            ->line(__('customer-feedback::notifications.newTicketBody', ['title' => $this->ticket->title]))
            ->action(__('customer-feedback::notifications.viewTicket'), route('customer-feedback.tickets.show', $this->ticket->id));
    }

    public function toArray(mixed $notifiable): array
    {
        return [
            'id'    => $this->ticket->id,
            'title' => $this->ticket->title,
            'type'  => $this->ticket->feedback_type,
        ];
    }
}
