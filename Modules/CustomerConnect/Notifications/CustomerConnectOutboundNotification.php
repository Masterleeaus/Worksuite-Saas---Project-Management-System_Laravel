<?php

namespace Modules\CustomerConnect\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class CustomerConnectOutboundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $message,
        protected string $channel = 'sms'
    ) {}

    public function via($notifiable)
    {
        return match ($this->channel) {
            'telegram' => ['telegram'],
            'sms' => $this->smsVia(),
            default => $this->smsVia(),
        };
    }

    protected function smsVia(): array
    {
        $via = [];

        // Prefer Twilio then Vonage.
        if (function_exists('sms_setting') && sms_setting() && sms_setting()->status) {
            $via[] = TwilioChannel::class;
        } elseif (function_exists('sms_setting') && sms_setting() && sms_setting()->nexmo_status) {
            $via[] = 'vonage';
        }

        return $via;
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())->content($this->message);
    }

    public function toVonage($notifiable)
    {
        return (new VonageMessage())->content($this->message)->unicode();
    }

    public function toTelegram($notifiable)
    {
        // Notification::route('telegram', <chat_id>) sets the destination internally
        return TelegramMessage::create()->content($this->message);
    }
}
