<?php

namespace Modules\CustomerConnect\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\CustomerConnect\Entities\Message;
use Modules\CustomerConnect\Services\Channels\ChannelSenderInterface;
use Modules\CustomerConnect\Services\Channels\OutboundMessage;
use Modules\CustomerConnect\Services\Premium\EventLogger;
use Modules\CustomerConnect\Services\Premium\TemplateSanitizer;

class SendThreadMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $messageId) {}

    public function handle(ChannelSenderInterface $sender): void
    {
        $message = Message::query()->with('thread.contact')->find($this->messageId);
        if (!$message) return;

        if ($message->status !== 'queued') return;

        $thread = $message->thread;
        if (!$thread || !$thread->contact) return;

        $channel = $thread->channel;

        // Sanitize body for channel
        $body = app(TemplateSanitizer::class)->sanitizeForChannel(
            $channel,
            (string)($message->body_text ?? '')
        );

        // Resolve destination from contact
        $to = match ($channel) {
            'email'    => (string)($thread->contact->email ?? ''),
            'sms'      => (string)($thread->contact->phone_e164 ?? ''),
            'whatsapp' => (string)($thread->contact->whatsapp_e164 ?? $thread->contact->phone_e164 ?? ''),
            'telegram' => (string)($thread->contact->telegram_chat_id ?? ''),
            default    => '',
        };

        if (trim($to) === '') {
            $message->status   = 'failed';
            $message->failed_at = now();
            $message->meta = array_merge((array)$message->meta, [
                'error' => 'Missing destination for channel ' . $channel,
            ]);
            $message->save();
            $this->logMessageEvent($message->id, 'failed', ['reason' => 'missing_destination']);
            return;
        }

        $subject = (string)($message->meta['subject'] ?? $thread->subject ?? 'Message');

        $payload = new OutboundMessage(
            companyId: $message->company_id,
            channel:   $channel,
            to:        $to,
            body:      $body,
            subject:   $subject,
            meta: [
                'thread_id'  => $thread->id,
                'message_id' => $message->id,
            ]
        );

        $message->status = 'sending';
        $message->save();

        $this->logMessageEvent($message->id, 'attempt', ['channel' => $channel, 'to' => $to]);

        $result = $sender->sendOutbound($payload);

        if ($result->ok) {
            $message->status              = 'sent';
            $message->sent_at             = now();
            $message->provider            = $result->provider;
            $message->provider_message_id = $result->providerMessageId;
            $this->logMessageEvent($message->id, 'sent', [
                'provider'            => $result->provider,
                'provider_message_id' => $result->providerMessageId,
            ]);
        } else {
            $message->status    = 'failed';
            $message->failed_at = now();
            $message->provider  = $result->provider;
            $message->meta = array_merge((array)$message->meta, [
                'error'   => $result->error,
                'details' => $result->details,
            ]);
            $this->logMessageEvent($message->id, 'failed', [
                'error'    => $result->error,
                'provider' => $result->provider,
            ]);
        }

        $message->save();
    }

    // FIX BUG 1: method is now INSIDE the class (was orphaned after closing brace)
    private function logMessageEvent(int $messageId, string $type, array $payload = []): void
    {
        try {
            app(EventLogger::class)->messageEvent($messageId, $type, $payload);
        } catch (\Throwable $e) {
            // never break queue
        }
    }
}
