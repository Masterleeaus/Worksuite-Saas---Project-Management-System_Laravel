<?php

namespace Modules\TitanTalk\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\TitanTalk\Models\TitanTalkMessage;
use Modules\TitanTalk\Models\TitanTalkNotificationPreference;

/**
 * TitanTalkNewMessage
 *
 * Notification dispatched to non-muted room members when a new TitanTalk room
 * message is sent.
 *
 * Delivery channels mirror the pattern of Worksuite's core App\Notifications\NewChat:
 *  - database  (always)
 *  - mail      (when user has email_notifications + per-room preference)
 *  - OneSignal push (when push_setting active, same as NewChat)
 *  - Beams push (when beams_push_status active, same as NewChat)
 */
class TitanTalkNewMessage extends Notification
{
    public function __construct(
        private readonly TitanTalkMessage $message
    ) {}

    /**
     * Delivery channels — mirrors NewChat::via() pattern.
     */
    public function via(mixed $notifiable): array
    {
        $via = ['database'];

        // Per-room preference (mail opt-in)
        $pref = TitanTalkNotificationPreference::where('user_id', $notifiable->id)
            ->where('room_id', $this->message->room_id)
            ->first();

        if (($pref?->notify_email ?? true) && $notifiable->email_notifications && $notifiable->email) {
            $via[] = 'mail';
        }

        // Push notifications — reuse existing push_setting() helper (same as NewChat)
        if (function_exists('push_setting')) {
            $push = push_setting();

            if ($push?->status === 'active' && class_exists(\NotificationChannels\OneSignal\OneSignalChannel::class)) {
                $via[] = \NotificationChannels\OneSignal\OneSignalChannel::class;
            }

            if ($push?->beams_push_status === 'active') {
                // Beams push — same pattern as NewChat
                try {
                    $dashboard = app(\App\Http\Controllers\DashboardController::class);
                    $dashboard->sendPushNotifications(
                        [[$notifiable->id]],
                        __('titantalk::titantalk.new_message_subject'),
                        \Illuminate\Support\Str::limit($this->message->body, 100)
                    );
                } catch (\Throwable) {}
            }
        }

        return $via;
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $roomName   = $this->message->room?->name ?? 'TitanTalk';
        $authorName = $this->message->author?->name ?? 'Someone';
        $url        = route('titan.talk.room.show', $this->message->room_id);

        return (new MailMessage)
            ->subject(__('titantalk::titantalk.new_message_subject') . " #{$roomName}")
            ->greeting("New message in #{$roomName}")
            ->line("{$authorName} said:")
            ->line(\Illuminate\Support\Str::limit($this->message->body, 200))
            ->action('Open TitanTalk', $url);
    }

    public function toDatabase(mixed $notifiable): array
    {
        return [
            'type'       => 'titan_talk_message',
            'room_id'    => $this->message->room_id,
            'message_id' => $this->message->id,
            'user_id'    => $this->message->user_id,
            'body'       => \Illuminate\Support\Str::limit($this->message->body, 100),
        ];
    }

    public function toOneSignal(): mixed
    {
        $roomName = $this->message->room?->name ?? 'TitanTalk';

        return \NotificationChannels\OneSignal\OneSignalMessage::create()
            ->setSubject(__('titantalk::titantalk.new_message_subject') . " #{$roomName}")
            ->setBody(\Illuminate\Support\Str::limit($this->message->body, 100))
            ->setUrl(route('titan.talk.room.show', $this->message->room_id));
    }
}

