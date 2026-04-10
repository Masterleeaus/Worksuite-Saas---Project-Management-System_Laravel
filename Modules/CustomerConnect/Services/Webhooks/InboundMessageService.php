<?php

namespace Modules\CustomerConnect\Services\Webhooks;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\CustomerConnect\Entities\ChannelIdentity;
use Modules\CustomerConnect\Entities\Contact;
use Modules\CustomerConnect\Entities\Thread;
use Modules\CustomerConnect\Entities\Message;
use Modules\CustomerConnect\Services\Inbox\ReplyStopper;

class InboundMessageService
{
    /**
     * Twilio inbound SMS/WhatsApp.
     */
    public function ingestTwilio(Request $request): void
    {
        $to = (string) ($request->input('To') ?? '');
        $from = (string) ($request->input('From') ?? '');
        $body = (string) ($request->input('Body') ?? '');
        $sid = (string) ($request->input('MessageSid') ?? $request->input('SmsMessageSid') ?? '');

        $channel = (stripos($to, 'whatsapp:') === 0 || stripos($from, 'whatsapp:') === 0) ? 'whatsapp' : 'sms';

        $identity = $this->resolveIdentity($channel, 'twilio', $to);
        if (!$identity) {
            return;
        }

        $companyId = (int) $identity->company_id;

        $media = [];
        $numMedia = (int) ($request->input('NumMedia') ?? 0);
        for ($i = 0; $i < $numMedia; $i++) {
            $urlKey = 'MediaUrl' . $i;
            $typeKey = 'MediaContentType' . $i;
            $url = $request->input($urlKey);
            if ($url) {
                $media[] = [
                    'url' => (string) $url,
                    'content_type' => (string) ($request->input($typeKey) ?? ''),
                ];
            }
        }

        $contact = $this->resolveContact($companyId, $channel, $from, null, null);
        $thread = $this->resolveThread($companyId, $contact->id, $channel, $from, null);

        $msg = Message::create([
            'company_id' => $companyId,
            'thread_id' => $thread->id,
            'direction' => 'inbound',
            'sender_user_id' => null,
            'body_text' => $body,
            'body_html' => null,
            'provider' => 'twilio',
            'provider_message_id' => $sid ?: null,
            'status' => 'delivered',
            'meta' => [
                'to' => $to,
                'from' => $from,
                'media' => $media,
                'raw' => $this->safePayload($request),
            ],
            'delivered_at' => Carbon::now(),
        ]);

        $this->touchThread($thread, $body);

        // Safety: stop queued deliveries if campaign is configured to stop on reply.
        try {
            app(ReplyStopper::class)->stopQueuedDeliveriesOnReply($companyId, $channel, $from);
        } catch (\Throwable $e) {
            // ignore
        }
    }

    /**
     * Vonage/Nexmo inbound SMS.
     */
    public function ingestVonage(Request $request): void
    {
        $to = (string) ($request->input('to') ?? $request->query('to') ?? '');
        $from = (string) ($request->input('msisdn') ?? $request->query('msisdn') ?? $request->input('from') ?? $request->query('from') ?? '');
        $text = (string) ($request->input('text') ?? $request->query('text') ?? '');
        $messageId = (string) ($request->input('messageId') ?? $request->query('messageId') ?? '');

        $channel = 'sms';

        $identity = $this->resolveIdentity($channel, 'vonage', $to);
        if (!$identity) {
            return;
        }

        $companyId = (int) $identity->company_id;

        $contact = $this->resolveContact($companyId, $channel, $from, null, null);
        $thread = $this->resolveThread($companyId, $contact->id, $channel, $from, null);

        Message::create([
            'company_id' => $companyId,
            'thread_id' => $thread->id,
            'direction' => 'inbound',
            'sender_user_id' => null,
            'body_text' => $text,
            'body_html' => null,
            'provider' => 'vonage',
            'provider_message_id' => $messageId ?: null,
            'status' => 'delivered',
            'meta' => [
                'to' => $to,
                'from' => $from,
                'raw' => $this->safePayload($request),
            ],
            'delivered_at' => Carbon::now(),
        ]);

        $this->touchThread($thread, $text);

        try {
            app(ReplyStopper::class)->stopQueuedDeliveriesOnReply($companyId, $channel, $from);
        } catch (\Throwable $e) {}
    }

    /**
     * Telegram inbound.
     */
    public function ingestTelegram(Request $request): void
    {
        $payload = $request->all();

        $message = $payload['message'] ?? $payload['edited_message'] ?? null;
        if (!$message) {
            return;
        }

        $chat = $message['chat'] ?? [];
        $from = $message['from'] ?? [];

        $chatId = (string) ($chat['id'] ?? '');
        if (!$chatId) {
            return;
        }

        $text = (string) ($message['text'] ?? ($message['caption'] ?? ''));

        $media = [];
        if (!empty($message['photo']) && is_array($message['photo'])) {
            $media[] = [
                'type' => 'photo',
                'file_id' => $message['photo'][count($message['photo'])-1]['file_id'] ?? null,
            ];
        }
        if (!empty($message['document'])) {
            $media[] = [
                'type' => 'document',
                'file_id' => $message['document']['file_id'] ?? null,
                'file_name' => $message['document']['file_name'] ?? null,
                'mime_type' => $message['document']['mime_type'] ?? null,
            ];
        }

        $identity = $this->resolveIdentity('telegram', 'telegram', 'telegram:default');
        if (!$identity) {
            return;
        }
        $companyId = (int) $identity->company_id;

        $contact = $this->resolveContact(
            $companyId,
            'telegram',
            $chatId,
            null,
            (string) ($from['username'] ?? ($from['first_name'] ?? 'Telegram'))
        );
        $thread = $this->resolveThread($companyId, $contact->id, 'telegram', $chatId, null);

        Message::create([
            'company_id' => $companyId,
            'thread_id' => $thread->id,
            'direction' => 'inbound',
            'sender_user_id' => null,
            'body_text' => $text,
            'body_html' => null,
            'provider' => 'telegram',
            'provider_message_id' => isset($message['message_id']) ? (string) $message['message_id'] : null,
            'status' => 'delivered',
            'meta' => [
                'chat_id' => $chatId,
                'from' => $from,
                'media' => $media,
                'raw' => $this->safePayload($request),
            ],
            'delivered_at' => Carbon::now(),
        ]);

        $this->touchThread($thread, $text ?: '[attachment]');

        try {
            app(ReplyStopper::class)->stopQueuedDeliveriesOnReply($companyId, 'telegram', $chatId);
        } catch (\Throwable $e) {}
    }

    private function resolveIdentity(string $channel, string $provider, string $inboundTo): ?ChannelIdentity
    {
        $to = trim((string) $inboundTo);
        return ChannelIdentity::query()
            ->where('channel', $channel)
            ->where('provider', $provider)
            ->where('inbound_address', $to)
            ->first();
    }

    private function resolveContact(int $companyId, string $channel, ?string $from, ?string $email, ?string $name): Contact
    {
        $query = Contact::query()->where('company_id', $companyId);

        if ($channel === 'email' && $email) {
            $query->where('email', $email);
        } elseif (in_array($channel, ['sms', 'whatsapp'], true) && $from) {
            $query->where('phone_e164', $from);
        } elseif ($channel === 'telegram' && $from) {
            $query->where('telegram_chat_id', $from);
        }

        $contact = $query->first();
        if ($contact) {
            return $contact;
        }

        return Contact::create([
            'company_id' => $companyId,
            'source_type' => 'unknown',
            'display_name' => $name ?: null,
            'email' => $email ?: null,
            'phone_e164' => $from ?: null,
            'whatsapp_e164' => $from ?: null,
            'telegram_chat_id' => $channel === 'telegram' ? ($from ?: null) : null,
            'meta' => [
                'created_from' => 'webhook',
                'channel' => $channel,
            ],
        ]);
    }

    private function resolveThread(int $companyId, int $contactId, string $channel, ?string $externalThreadId, ?string $subject): Thread
    {
        $query = Thread::query()
            ->where('company_id', $companyId)
            ->where('contact_id', $contactId)
            ->where('channel', $channel);

        if ($externalThreadId) {
            $query->where('external_thread_id', $externalThreadId);
        }

        $thread = $query->first();
        if ($thread) {
            return $thread;
        }

        return Thread::create([
            'company_id' => $companyId,
            'contact_id' => $contactId,
            'channel' => $channel,
            'external_thread_id' => $externalThreadId,
            'subject' => $subject,
            'status' => 'open',
            'last_message_at' => Carbon::now(),
            'last_message_preview' => null,
        ]);
    }

    private function touchThread(Thread $thread, string $bodyText): void
    {
        $thread->last_message_at = Carbon::now();
        $thread->last_message_preview = mb_substr(trim($bodyText), 0, 180);
        $thread->save();
    }

    private function safePayload(Request $request): array
    {
        $payload = $request->all();

        // Remove huge media arrays when present
        foreach (['Media', 'media', 'attachments', 'photo', 'document'] as $k) {
            if (isset($payload[$k]) && is_array($payload[$k]) && count($payload[$k]) > 50) {
                $payload[$k] = '[trimmed]';
            }
        }

        return $payload;
    }
}
