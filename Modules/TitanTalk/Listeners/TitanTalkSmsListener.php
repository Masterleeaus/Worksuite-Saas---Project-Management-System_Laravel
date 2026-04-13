<?php

namespace Modules\TitanTalk\Listeners;

use Modules\TitanTalk\Events\TitanTalkMentionEvent;
use Modules\TitanTalk\Services\TitanTalkService;

/**
 * TitanTalkSmsListener
 *
 * Listens for TitanTalkMentionEvent and dispatches an optional SMS escalation
 * via the Sms module's CleaningNotificationService.
 *
 * SMS escalation is only sent when:
 *  - The Sms module is installed (class_exists check done inside TitanTalkService)
 *  - The 'titan-talk-mention' slug is registered in SmsNotificationSlug
 *  - The user has a valid mobile number and has not opted out
 *
 * This follows the exact same pattern as Modules\Sms\Listeners\SmsTicketListener.
 */
class TitanTalkSmsListener
{
    public function __construct(private readonly TitanTalkService $service) {}

    public function handle(TitanTalkMentionEvent $event): void
    {
        try {
            $user    = $event->mentionedUser;
            $message = $event->message;
            $room    = $message->room;

            $roomName   = $room?->name ?? 'TitanTalk';
            $authorName = $message->author?->name ?? 'Someone';
            $preview    = \Illuminate\Support\Str::limit($message->body, 60);

            $text = "{$authorName} mentioned you in #{$roomName}: \"{$preview}\"";

            $this->service->sendSmsEscalation($user, $text);
        } catch (\Throwable) {
            // Never break TitanTalk due to SMS failure
        }
    }
}
