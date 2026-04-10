<?php

namespace Modules\CustomerConnect\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\CustomerConnect\Entities\Delivery;
use Modules\CustomerConnect\Entities\Message;
use Modules\CustomerConnect\Entities\Suppression;
use Modules\CustomerConnect\Entities\Thread;
use Modules\CustomerConnect\Entities\Unsubscribe;
use Modules\CustomerConnect\Services\Channels\ChannelSenderInterface;
use Modules\CustomerConnect\Services\Premium\AlertService;
use Modules\CustomerConnect\Services\Premium\EventLogger;
use Modules\CustomerConnect\Services\Premium\TemplateSanitizer;
use Modules\CustomerConnect\Services\Safety\DailyCapService;
use Modules\CustomerConnect\Services\Safety\QuietHoursService;

class SendDelivery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $deliveryId) {}

    public function handle(
        ChannelSenderInterface $sender,
        QuietHoursService $quiet,
        DailyCapService $caps
    ): void {
        $delivery = Delivery::find($this->deliveryId);
        if (!$delivery) {
            return;
        }

        if (!in_array($delivery->status, ['queued', 'pending'], true)) {
            return;
        }

        // Safety: suppression / unsubscribe check
        $isSuppressed = Suppression::query()
            ->where('company_id', $delivery->company_id)
            ->where(function ($q) use ($delivery) {
                if ($delivery->email) $q->orWhere('email', $delivery->email);
                if ($delivery->phone) $q->orWhere('phone', $delivery->phone);
                if ($delivery->telegram_user_id) $q->orWhere('telegram_user_id', $delivery->telegram_user_id);
            })->exists();

        $isUnsub = Unsubscribe::query()
            ->where('company_id', $delivery->company_id)
            ->where('channel', $delivery->channel)
            ->where(function ($q) use ($delivery) {
                if ($delivery->email) $q->orWhere('email', $delivery->email);
                if ($delivery->phone) $q->orWhere('phone', $delivery->phone);
            })->exists();

        if ($isSuppressed || $isUnsub) {
            $delivery->status = 'skipped';
            $delivery->error  = $isSuppressed ? 'Suppressed' : 'Unsubscribed';
            $delivery->save();
            $this->logDeliveryEvent($delivery->id, 'skipped', ['reason' => $delivery->error]);
            return;
        }

        // Guard: quiet hours
        if ($quiet->isQuietNow()) {
            $delivery->status        = 'queued';
            $delivery->scheduled_for = Carbon::now()->addMinutes(30);
            $delivery->error         = 'Quiet hours';
            $delivery->save();
            $this->logDeliveryEvent($delivery->id, 'deferred', ['reason' => 'quiet_hours']);
            return;
        }

        // Guard: daily caps
        if ($caps->isOverCap($delivery->company_id, $delivery->channel)) {
            $tomorrow                = Carbon::now()->addDay()->startOfDay()->addHours(9);
            $delivery->status        = 'queued';
            $delivery->scheduled_for = $tomorrow;
            $delivery->error         = 'Daily cap reached';
            $delivery->save();
            $this->logDeliveryEvent($delivery->id, 'deferred', ['reason' => 'daily_cap']);
            return;
        }

        // Ensure thread + message record exists for inbox view
        try {
            if (empty($delivery->thread_id) && !empty($delivery->contact_id)) {
                $thread = Thread::query()->firstOrCreate([
                    'company_id'         => $delivery->company_id,
                    'contact_id'         => $delivery->contact_id,
                    'channel'            => $delivery->channel,
                    'external_thread_id' => $delivery->to_address ?? null,
                ], [
                    'status'               => 'open',
                    'last_message_at'      => Carbon::now(),
                    'last_message_preview' => '',
                ]);
                $delivery->thread_id = $thread->id;
            }

            if (empty($delivery->message_id) && !empty($delivery->thread_id)) {
                $msg = Message::create([
                    'company_id'         => $delivery->company_id,
                    'thread_id'          => $delivery->thread_id,
                    'direction'          => 'outbound',
                    'sender_user_id'     => null,
                    'body_text'          => (string)($delivery->body ?? ''),
                    'provider'           => null,
                    'provider_message_id'=> null,
                    'status'             => 'queued',
                    'meta'               => [
                        'subject'              => $delivery->subject ?? null,
                        'source'               => 'campaign_delivery',
                        'campaign_delivery_id' => $delivery->id,
                    ],
                ]);
                $delivery->message_id = $msg->id;
            }

            if (!empty($delivery->thread_id)) {
                Thread::query()->where('id', $delivery->thread_id)->update([
                    'last_message_at'      => Carbon::now(),
                    'last_message_preview' => mb_substr(trim((string)($delivery->body ?? '')), 0, 180),
                    'status'               => 'open',
                ]);
            }
        } catch (\Throwable $e) {
            // Never break the send path
        }

        $delivery->status          = 'sending';
        $delivery->attempts        = (int)$delivery->attempts + 1;
        $delivery->last_attempt_at = Carbon::now();
        $delivery->save();

        $this->logDeliveryEvent($delivery->id, 'attempt', ['attempt' => $delivery->attempts]);

        $result = $sender->send($delivery);

        if ($result->ok) {
            $delivery->status              = 'sent';
            $delivery->provider            = $result->provider;
            $delivery->provider_message_id = $result->providerMessageId;
            $delivery->provider_response   = $result->providerResponse;
            $delivery->error               = null;
            $delivery->sent_at             = Carbon::now();

            if (!empty($delivery->message_id)) {
                Message::query()->where('id', $delivery->message_id)->update([
                    'status'             => 'sent',
                    'provider'           => $result->provider,
                    'provider_message_id'=> $result->providerMessageId,
                    'sent_at'            => Carbon::now(),
                ]);
            }

            $this->logDeliveryEvent($delivery->id, 'sent', [
                'provider'            => $result->provider,
                'provider_message_id' => $result->providerMessageId,
            ]);
        } else {
            $delivery->status              = 'failed';
            $delivery->provider            = $result->provider;
            $delivery->provider_message_id = $result->providerMessageId;
            $delivery->provider_response   = $result->providerResponse;
            $delivery->error               = $result->error ?: 'Unknown send failure';

            if (!empty($delivery->message_id)) {
                // Reload meta safely — no extra DB query via optional()
                $existingMeta = (array)(Message::query()
                    ->where('id', $delivery->message_id)
                    ->value('meta') ?? []);

                Message::query()->where('id', $delivery->message_id)->update([
                    'status'             => 'failed',
                    'provider'           => $result->provider,
                    'provider_message_id'=> $result->providerMessageId,
                    'failed_at'          => Carbon::now(),
                    'meta'               => array_merge($existingMeta, ['error' => $delivery->error]),
                ]);
            }

            $this->logDeliveryEvent($delivery->id, 'failed', [
                'error'    => $delivery->error,
                'provider' => $result->provider,
            ]);
        }

        $delivery->save();
    }

    // FIX BUG 1: method is now INSIDE the class (was orphaned after closing brace)
    private function logDeliveryEvent(int $deliveryId, string $type, array $payload = []): void
    {
        try {
            app(EventLogger::class)->deliveryEvent($deliveryId, $type, $payload);
        } catch (\Throwable $e) {
            // never break queue
        }
    }
}
