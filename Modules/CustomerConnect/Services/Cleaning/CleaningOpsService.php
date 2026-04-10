<?php

namespace Modules\CustomerConnect\Services\Cleaning;

use Illuminate\Support\Facades\Log;
use Modules\CustomerConnect\Entities\Contact;
use Modules\CustomerConnect\Entities\Thread;
use Modules\CustomerConnect\Entities\Message;
use Modules\CustomerConnect\Jobs\SendThreadMessage;
use Modules\CustomerConnect\Services\Premium\PhoneFormatter;

class CleaningOpsService
{
    public function handleStatusChanged(array $payload): void
    {
        if (!config('customerconnect.cleaning_ops.enabled')) {
            return;
        }

        $event = (string)($payload['event'] ?? 'status_changed');
        $status = strtolower((string)($payload['status'] ?? ''));
        if ($status === '') return;

        $companyId = (int)($payload['company_id'] ?? 0);
        $userId = (int)($payload['user_id'] ?? 0);

        $contact = $this->resolveContact($payload, $companyId);
        if (!$contact) {
            Log::debug('CustomerConnect CleaningOps: no contact resolved', ['payload' => $payload]);
            return;
        }

        // Arrival notice
        if (config('customerconnect.cleaning_ops.arrival_notice.enabled')) {
            $triggers = (array)config('customerconnect.cleaning_ops.arrival_notice.trigger_statuses', []);
            if (in_array($status, $triggers, true)) {
                $this->queueOutbound(
                    $companyId,
                    $userId,
                    $contact->id,
                    (string)($payload['channel'] ?? 'sms'),
                    $this->arrivalMessage($payload),
                    ['flow' => 'arrival_notice', 'event' => $event, 'status' => $status]
                );
                return;
            }
        }

        // Post-clean review request
        if (config('customerconnect.cleaning_ops.post_clean_review.enabled')) {
            $triggers = (array)config('customerconnect.cleaning_ops.post_clean_review.trigger_statuses', []);
            if (in_array($status, $triggers, true)) {
                $delay = (int)config('customerconnect.cleaning_ops.post_clean_review.delay_minutes', 30);
                $this->queueOutbound(
                    $companyId,
                    $userId,
                    $contact->id,
                    (string)($payload['channel'] ?? 'sms'),
                    $this->reviewMessage($payload),
                    ['flow' => 'post_clean_review', 'event' => $event, 'status' => $status],
                    now()->addMinutes(max(0,$delay))
                );
            }
        }

        // Quality follow-up
        if (config('customerconnect.cleaning_ops.quality_followup.enabled')) {
            $triggers = (array)config('customerconnect.cleaning_ops.quality_followup.trigger_statuses', []);
            if (in_array($status, $triggers, true)) {
                $delayHrs = (int)config('customerconnect.cleaning_ops.quality_followup.delay_hours', 4);
                $this->queueOutbound(
                    $companyId,
                    $userId,
                    $contact->id,
                    (string)($payload['channel'] ?? 'sms'),
                    $this->qualityMessage($payload),
                    ['flow' => 'quality_followup', 'event' => $event, 'status' => $status],
                    now()->addHours(max(0,$delayHrs))
                );
            }
        }
    }

    private function resolveContact(array $payload, int $companyId): ?Contact
    {
        $contactId = (int)($payload['contact_id'] ?? 0);
        if ($contactId > 0) {
            return Contact::query()->where('company_id', $companyId)->find($contactId);
        }

        $phone = (string)($payload['phone'] ?? $payload['phone_e164'] ?? '');
        $email = (string)($payload['email'] ?? '');
        $pf = new PhoneFormatter();
        $e164 = $phone ? $pf->toE164($phone) : '';

        if ($e164) {
            return Contact::query()
                ->where('company_id', $companyId)
                ->where(function ($q) use ($e164) {
                    $q->where('phone_e164', $e164)
                      ->orWhere('whatsapp_e164', $e164);
                })
                ->first();
        }

        if ($email) {
            return Contact::query()->where('company_id', $companyId)->where('email', $email)->first();
        }

        return null;
    }

    private function queueOutbound(int $companyId, int $userId, int $contactId, string $channel, string $body, array $meta = [], $delayUntil = null): void
    {
        $channel = $channel ?: 'sms';

        $thread = Thread::query()
            ->where('company_id', $companyId)
            ->where('contact_id', $contactId)
            ->where('channel', $channel)
            ->first();

        if (!$thread) {
            $thread = Thread::create([
                'company_id' => $companyId,
                'contact_id' => $contactId,
                'channel' => $channel,
                'subject' => 'Cleaning Updates',
                'status' => 'open',
            ]);
        }

        $msg = Message::create([
            'company_id' => $companyId,
            'thread_id' => $thread->id,
            'direction' => 'outbound',
            'sender_user_id' => $userId ?: null,
            'body_text' => $body,
            'status' => 'queued',
            'meta' => array_merge(['vertical' => 'cleaning'], $meta),
            'audit_meta' => ['source' => 'cleaning_ops'],
        ]);

        // Update thread preview
        $thread->last_message_at = now();
        $thread->last_message_preview = mb_substr(trim($body), 0, 180);
        $thread->save();

        $job = SendThreadMessage::dispatch($msg->id);
        if ($delayUntil) {
            $job->delay($delayUntil);
        }
    }

    private function arrivalMessage(array $payload): string
    {
        $name = (string)($payload['client_name'] ?? $payload['customer_name'] ?? '');
        $window = (string)($payload['eta_window'] ?? '');
        $addr = (string)($payload['address'] ?? '');
        $parts = [];
        $parts[] = $name ? "Hi {$name} — your cleaner is on the way." : "Hi — your cleaner is on the way.";
        if ($window) $parts[] = "ETA: {$window}.";
        if ($addr) $parts[] = "Address: {$addr}.";
        $parts[] = "Reply if you have access notes (gate code, parking, pets).";
        return implode(' ', $parts);
    }

    private function reviewMessage(array $payload): string
    {
        $name = (string)($payload['client_name'] ?? $payload['customer_name'] ?? '');
        $link = (string)($payload['review_link'] ?? '');
        $beforeAfter = (string)($payload['before_after_link'] ?? '');
        $parts = [];
        $parts[] = $name ? "Thanks {$name} — hope you loved today’s clean." : "Thanks — hope you loved today’s clean.";
        if ($beforeAfter) $parts[] = "Before/after photos: {$beforeAfter}";
        if ($link) $parts[] = "If you can, please leave a quick review: {$link}";
        else $parts[] = "If you can, a quick review would really help us.";
        return implode(' ', $parts);
    }

    private function qualityMessage(array $payload): string
    {
        $name = (string)($payload['client_name'] ?? $payload['customer_name'] ?? '');
        $parts = [];
        $parts[] = $name ? "Hi {$name} — quick check-in after your clean." : "Hi — quick check-in after your clean.";
        $parts[] = "Did we miss anything or is there any spot you’d like us to touch up?";
        $parts[] = "Reply with a note (or photo) and we’ll sort it fast.";
        return implode(' ', $parts);
    }
}
