<?php

namespace Modules\Sms\Services;

use Modules\Sms\Entities\SmsChannelPreference;
use Modules\Sms\Entities\SmsNotificationLog;
use Modules\Sms\Entities\SmsNotificationSetting;
use Modules\Sms\Entities\SmsOptOut;
use Modules\Sms\Enums\SmsNotificationSlug;

/**
 * Handles sending cleaning-specific SMS/WhatsApp notifications via Twilio.
 *
 * Supports per-client channel preferences (SMS or WhatsApp), automatic
 * WhatsApp→SMS fallback, opt-out checks, and delivery logging.
 */
class CleaningNotificationService
{
    /**
     * Send a cleaning notification to a user/client.
     *
     * @param  \App\Models\User  $notifiable
     * @param  SmsNotificationSlug  $slug
     * @param  string  $message  Fully resolved message text
     * @return void
     */
    public function send($notifiable, SmsNotificationSlug $slug, string $message): void
    {
        $settings = sms_setting();

        if (!$settings) {
            return;
        }

        // Check whether this trigger is enabled
        $notifSetting = SmsNotificationSetting::where('slug', $slug->value)
            ->where('company_id', $notifiable->company_id ?? null)
            ->first();

        if (!$notifSetting || $notifSetting->send_sms !== 'yes') {
            return;
        }

        if (empty($notifiable->mobile) || empty($notifiable->country_phonecode)) {
            return;
        }

        $phoneNumber = '+' . ltrim($notifiable->country_phonecode, '+') . $notifiable->mobile;

        // Honour STOP opt-outs (SMS channel only)
        if (SmsOptOut::isOptedOut($phoneNumber, $notifiable->company_id ?? null)) {
            return;
        }

        // Determine preferred channel
        $preferredChannel = SmsChannelPreference::forUser(
            $notifiable->id,
            $notifiable->company_id ?? null
        );

        if ($preferredChannel === 'whatsapp' && $settings->whatsapp_status) {
            $sent = $this->sendWhatsApp($settings, $phoneNumber, $message, $slug, $notifiable);

            if (!$sent) {
                // Fallback to SMS
                $this->sendSms($settings, $phoneNumber, $message, $slug, $notifiable);
            }
        } else {
            $this->sendSms($settings, $phoneNumber, $message, $slug, $notifiable);
        }
    }

    private function sendWhatsApp($settings, string $toNumber, string $message, SmsNotificationSlug $slug, $notifiable): bool
    {
        if (!$settings->account_sid || !$settings->auth_token || !$settings->whatapp_from_number) {
            return false;
        }

        try {
            $twilio = new \Twilio\Rest\Client($settings->account_sid, $settings->auth_token);
            $sent = $twilio->messages->create(
                'whatsapp:' . $toNumber,
                [
                    'from' => 'whatsapp:' . $settings->whatapp_from_number,
                    'body' => $message,
                ]
            );

            $this->log($notifiable, $toNumber, 'whatsapp', $slug, $message, 'delivered', $sent->sid ?? null);

            return true;
        } catch (\Throwable $e) {
            $this->log($notifiable, $toNumber, 'whatsapp', $slug, $message, 'failed', null, $e->getMessage());
            return false;
        }
    }

    private function sendSms($settings, string $toNumber, string $message, SmsNotificationSlug $slug, $notifiable): void
    {
        if (!$settings->status || !$settings->account_sid || !$settings->auth_token || !$settings->from_number) {
            return;
        }

        try {
            $twilio = new \Twilio\Rest\Client($settings->account_sid, $settings->auth_token);
            $sent = $twilio->messages->create(
                $toNumber,
                [
                    'from' => $settings->from_number,
                    'body' => $message,
                ]
            );

            $this->log($notifiable, $toNumber, 'sms', $slug, $message, 'delivered', $sent->sid ?? null);
        } catch (\Throwable $e) {
            $this->log($notifiable, $toNumber, 'sms', $slug, $message, 'failed', null, $e->getMessage());
        }
    }

    private function log($notifiable, string $toNumber, string $channel, SmsNotificationSlug $slug, string $message, string $status, ?string $twilioSid = null, ?string $error = null): void
    {
        try {
            SmsNotificationLog::create([
                'company_id'   => $notifiable->company_id ?? null,
                'user_id'      => $notifiable->id ?? null,
                'to_number'    => $toNumber,
                'channel'      => $channel,
                'trigger_type' => $slug->value,
                'message'      => $message,
                'status'       => $status,
                'twilio_sid'   => $twilioSid,
                'error_message' => $error,
            ]);
        } catch (\Throwable $e) {
            // Logging must never break the notification flow
        }
    }

    /**
     * Build a cleaning message from the notification setting template or
     * fall back to the default language string.
     *
     * Replaces [Client], [Name], [Time], [Address], [link] placeholders.
     */
    public static function resolveTemplate(SmsNotificationSlug $slug, ?int $companyId, array $vars): string
    {
        $setting = SmsNotificationSetting::where('slug', $slug->value)
            ->where('company_id', $companyId)
            ->first();

        $template = ($setting && !empty($setting->custom_template))
            ? $setting->custom_template
            : __($slug->translationString(), $vars);

        // Replace [Placeholder] style variables
        foreach ($vars as $key => $value) {
            $template = str_replace('[' . ucfirst($key) . ']', $value, $template);
            $template = str_replace(':' . $key, $value, $template);
        }

        return $template;
    }
}
