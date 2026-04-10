<?php

namespace Modules\CustomerConnect\Services\Premium;

use Illuminate\Support\Facades\Notification;
use Modules\CustomerConnect\Notifications\CustomerConnectAlertNotification;

class AlertNotifier
{
    public function notifyTelegram(string $title, string $body, array $context = []): void
    {
        $chatId = config('customerconnect.alerts.telegram_chat_id');
        if (!$chatId) {
            return;
        }

        Notification::route('telegram', $chatId)
            ->notify(new CustomerConnectAlertNotification($title, $body, $context));
    }
}
