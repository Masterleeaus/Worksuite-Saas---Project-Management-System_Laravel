<?php

namespace Modules\CustomerConnect\Services\Channels;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Modules\CustomerConnect\Entities\Delivery;
use Modules\CustomerConnect\Mail\CustomerConnectCampaignMail;
use Modules\CustomerConnect\Notifications\CustomerConnectOutboundNotification;

/**
 * Default sender implementation for WorkSuite.
 *
 * Email:    Laravel Mail (WorkSuite SMTP settings).
 * SMS:      Delegates to the Sms module — reads active gateway from sms_setting()
 *           and routes to exactly ONE provider (Twilio OR Vonage, not both).
 * WhatsApp: Routes via Twilio whatsapp: prefix.
 * Telegram: Routes via telegram-notifications channel.
 *
 * ISSUE C FIX: Original code routed SMS to Twilio AND Vonage simultaneously,
 *              causing double-sends when both providers are configured.
 *              Now reads the active gateway flag and routes to exactly one.
 */
class WorkSuiteSmsModuleSender implements ChannelSenderInterface
{
    public function send(Delivery $delivery): SendResult
    {
        $channel = (string)($delivery->channel ?? '');
        $to      = (string)($delivery->to ?? '');
        $subject = $delivery->subject ?? null;
        $body    = (string)($delivery->body ?? '');

        $msg = new OutboundMessage(
            companyId: $delivery->company_id,
            channel:   $channel,
            to:        $to,
            body:      $body,
            subject:   $subject,
            meta: [
                'delivery_id' => $delivery->id,
                'run_id'      => $delivery->run_id,
                'step_id'     => $delivery->step_id,
            ]
        );

        return $this->sendOutbound($msg);
    }

    public function sendOutbound(OutboundMessage $message): SendResult
    {
        $channel = strtolower($message->channel);

        try {
            return match ($channel) {
                'email'    => $this->sendEmail($message->to, $message->subject ?? 'Message', $message->body),
                'sms'      => $this->sendSms($message->to, $message->body),
                'whatsapp' => $this->sendWhatsapp($message->to, $message->body),
                'telegram' => $this->sendTelegram($message->to, $message->body),
                default    => SendResult::failed('Unsupported channel: ' . $channel),
            };
        } catch (\Throwable $e) {
            return SendResult::failed($e->getMessage(), ['exception' => get_class($e)]);
        }
    }

    protected function sendEmail(string $to, string $subject, string $body): SendResult
    {
        Mail::to($to)->send(new CustomerConnectCampaignMail($subject, $body));
        return SendResult::sent(['provider' => 'mail']);
    }

    protected function sendSms(string $to, string $body): SendResult
    {
        $phone = $this->normalizePhone($to);
        $via   = $this->resolveActiveSmsVia();

        if (empty($via)) {
            return SendResult::failed('No active SMS provider configured in Sms module settings.');
        }

        // Route to exactly ONE provider — whichever is active (ISSUE C FIX)
        Notification::route($via[0], $phone)
            ->notify(new CustomerConnectOutboundNotification('sms', $body));

        return SendResult::sent(['provider' => $via[0]]);
    }

    protected function sendWhatsapp(string $to, string $body): SendResult
    {
        $phone = $this->normalizePhone($to);

        // WhatsApp always routes through Twilio whatsapp: prefix
        Notification::route('twilio', 'whatsapp:' . $phone)
            ->notify(new CustomerConnectOutboundNotification('whatsapp', $body));

        return SendResult::sent(['provider' => 'twilio_whatsapp']);
    }

    protected function sendTelegram(string $chatId, string $body): SendResult
    {
        Notification::route('telegram', $chatId)
            ->notify(new CustomerConnectOutboundNotification('telegram', $body));

        return SendResult::sent(['provider' => 'telegram']);
    }

    /**
     * Resolve the single active SMS notification channel from the Sms module settings.
     * Returns e.g. ['twilio'] or ['vonage'] — never both.
     *
     * ISSUE C FIX: the original code dispatched to both channels simultaneously.
     * Now we check Sms module setting flags in priority order and return only one.
     */
    protected function resolveActiveSmsVia(): array
    {
        try {
            if (function_exists('sms_setting')) {
                $setting = sms_setting();
                if ($setting) {
                    // Twilio takes precedence if enabled
                    if (!empty($setting->status)) {
                        return ['twilio'];
                    }
                    // Vonage / Nexmo second
                    if (!empty($setting->nexmo_status)) {
                        return ['vonage'];
                    }
                    // MSG91
                    if (!empty($setting->msg91_status)) {
                        return ['msg91'];
                    }
                }
            }
        } catch (\Throwable $e) {
            // Sms module not present — fall through to empty
        }

        return [];
    }

    protected function normalizePhone(string $phone): string
    {
        $phone = trim($phone);

        if (str_starts_with($phone, 'whatsapp:')) {
            $phone = substr($phone, strlen('whatsapp:'));
        }

        if ($phone === '') return $phone;

        if ($phone[0] !== '+') {
            $digits = preg_replace('/\D+/', '', $phone) ?? '';
            if ($digits !== '') {
                $phone = '+' . $digits;
            }
        }

        return $phone;
    }
}
