<?php

namespace Modules\TitanTalk\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\TitanTalk\Models\TitanTalkMessage;

/**
 * TitanTalkMentionEvent
 *
 * Fired when a user is @mentioned in a TitanTalk room message.
 * Mirrors the pattern of App\Events\NewMentionChatEvent (used by Worksuite DM mentions).
 * Broadcast on the mentioned user's private channel so the client can show a badge.
 */
class TitanTalkMentionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly TitanTalkMessage $message,
        public readonly User $mentionedUser
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        // Each user's private notification channel
        return new PrivateChannel('titan-talk.user.' . $this->mentionedUser->id);
    }

    public function broadcastAs(): string
    {
        return 'mention.received';
    }

    public function broadcastWith(): array
    {
        return [
            'message_id'       => $this->message->id,
            'room_id'          => $this->message->room_id,
            'from_user_id'     => $this->message->user_id,
            'from_user_name'   => $this->message->author?->name ?? 'Someone',
            'body_preview'     => \Illuminate\Support\Str::limit($this->message->body, 80),
        ];
    }
}
