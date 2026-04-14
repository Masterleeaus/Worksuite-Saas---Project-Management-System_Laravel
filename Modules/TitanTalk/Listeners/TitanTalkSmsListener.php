<?php

namespace Modules\TitanTalk\Listeners;

use Modules\TitanTalk\Events\TitanTalkMentionEvent;
use Modules\TitanTalk\Models\TitanTalkNotificationPreference;
use Modules\TitanTalk\Services\TitanTalkService;

/**
 * TitanTalkSmsListener
 *
 * Listens for TitanTalkMentionEvent and dispatches an optional SMS escalation
 * via the Sms module's CleaningNotificationService.
 *
 * SMS escalation is only sent when ALL of the following are true:
 *  1. The Sms module is installed (class_exists guarded inside TitanTalkService)
 *  2. The 'titan-talk-mention' slug is registered in SmsNotificationSlug
 *  3. titantalk.sms_escalation.enabled is true
 *  4. The room type is in titantalk.sms_escalation.room_types (e.g. dispatch, issue, service_job)
 *  5. The mentioned user has notify_sms = true in their notification preference
 *     (global preference: room_id = NULL; room-specific preference takes precedence)
 *
 * This follows the exact same pattern as Modules\Sms\Listeners\SmsTicketListener.
 */
class TitanTalkSmsListener
{
    public function __construct(private readonly TitanTalkService $service) {}

    public function handle(TitanTalkMentionEvent $event): void
    {
        try {
            // Guard 1: global config kill-switch
            if (!config('titantalk.sms_escalation.enabled', false)) {
                return;
            }

            $user    = $event->mentionedUser;
            $message = $event->message;
            $room    = $message->room;

            if (!$room) {
                return;
            }

            // Guard 2: room type must be in the configured escalation list
            $escalationRoomTypes = config('titantalk.sms_escalation.room_types', []);
            if (!in_array($room->type, $escalationRoomTypes, true)) {
                return;
            }

            // Guard 3: per-user / per-room notification preference
            // Check room-specific preference first, then fall back to global (room_id = null)
            $pref = TitanTalkNotificationPreference::where('user_id', $user->id)
                ->where('room_id', $room->id)
                ->first()
                ?? TitanTalkNotificationPreference::where('user_id', $user->id)
                    ->whereNull('room_id')
                    ->first();

            if ($pref && !$pref->notify_sms) {
                return;
            }
            // If no preference row exists, default is do-not-escalate (opt-in, not opt-out)
            if (!$pref || !$pref->notify_sms) {
                return;
            }

            $roomName   = $room->name;
            $authorName = $message->author?->name ?? 'Someone';
            $preview    = \Illuminate\Support\Str::limit($message->body, 60);

            $text = "{$authorName} mentioned you in #{$roomName}: \"{$preview}\"";

            $this->service->sendSmsEscalation($user, $text);
        } catch (\Throwable) {
            // Never break TitanTalk due to SMS failure
        }
    }
}
