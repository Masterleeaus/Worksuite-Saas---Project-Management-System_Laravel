<?php

namespace Modules\CustomerConnect\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class CustomerConnectAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $title,
        protected string $body,
        protected array $context = []
    ) {}

    public function via($notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable): TelegramMessage
    {
        $lines = [
            '🚨 *CustomerConnect Alert*',
            '*' . $this->escape($this->title) . '*',
            $this->escape($this->body),
        ];

        if (!empty($this->context)) {
            $lines[] = '';
            foreach ($this->context as $k => $v) {
                $lines[] = '• ' . $this->escape((string)$k) . ': ' . $this->escape(is_scalar($v) ? (string)$v : json_encode($v));
            }
        }

        return TelegramMessage::create()
            ->to($notifiable)
            ->content(implode("\n", $lines))
            ->options(['parse_mode' => 'Markdown']);
    }

    private function escape(string $s): string
    {
        // Minimal markdown escaping for Telegram.
        return str_replace(['*', '_', '[', ']'], ['\*', '\_', '\[', '\]'], $s);
    }
}
