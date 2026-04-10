<?php

namespace Modules\ChattingModule\Notifications;

use App\Models\UserChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Fired when a new chat message is received.
 * Sends an in-app notification and an optional email digest.
 */
class NewChatMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly UserChat $chat
    ) {}

    /**
     * Notification channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Mail representation — used for daily unread-message digest.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $senderName = optional($this->chat->fromUser)->name ?? 'Someone';
        $bookingRef = $this->chat->booking_id
            ? ' (Booking #' . $this->chat->booking_id . ')'
            : '';

        return (new MailMessage)
            ->subject('New message from ' . $senderName . $bookingRef)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($senderName . ' sent you a message' . $bookingRef . ':')
            ->line('"' . e($this->chat->message ?? '📎 Attachment') . '"')
            ->action('View Message', url('/messages'))
            ->line('Log in to reply.');
    }

    /**
     * Database / in-app notification payload.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'new_chat_message',
            'chat_id'    => $this->chat->id,
            'booking_id' => $this->chat->booking_id,
            'sender_id'  => $this->chat->from,
            'message'    => e($this->chat->message ?? ''),
            'channel'    => $this->chat->channel,
        ];
    }
}
