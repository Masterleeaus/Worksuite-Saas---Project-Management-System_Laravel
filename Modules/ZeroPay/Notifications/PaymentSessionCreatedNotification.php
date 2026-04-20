<?php

namespace Modules\ZeroPay\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\ZeroPay\Models\ZeroPaySession;

class PaymentSessionCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly ZeroPaySession $session)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('ZeroPay session created')
            ->line('A new ZeroPay payment session is available.')
            ->action('Open Session', route('zeropay.session.show', $this->session->public_token));
    }
}
