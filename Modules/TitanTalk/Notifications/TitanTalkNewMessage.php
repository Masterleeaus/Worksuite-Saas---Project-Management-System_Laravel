<?php

namespace Modules\TitanTalk\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\TitanTalk\Models\TitanTalkMessage;

class TitanTalkNewMessage extends Notification
{
    public function __construct(
        private readonly TitanTalkMessage $message
    ) {}

    public function via(mixed $notifiable): array
    {
        $via = ['database'];

        $pref = \Modules\TitanTalk\Models\TitanTalkNotificationPreference::where('user_id', $notifiable->id)
            ->where('room_id', $this->message->room_id)
            ->first();

        if ($pref?->notify_email && $notifiable->email) {
            $via[] = 'mail';
        }

        return $via;
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $roomName = $this->message->room?->name ?? 'TitanTalk';
        $authorName = $this->message->author?->name ?? 'Someone';

        return (new MailMessage)
            ->subject("New message in #{$roomName}")
            ->line("{$authorName} sent a message in #{$roomName}:")
            ->line($this->message->body)
            ->action('View in TitanTalk', route('titan.talk.room.show', $this->message->room_id));
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
}
