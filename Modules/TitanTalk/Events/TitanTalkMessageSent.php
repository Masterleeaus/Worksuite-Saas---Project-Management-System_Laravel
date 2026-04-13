<?php

namespace Modules\TitanTalk\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\TitanTalk\Models\TitanTalkMessage;

class TitanTalkMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly TitanTalkMessage $message
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('titan-talk.room.' . $this->message->room_id);
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->message->id,
            'room_id'    => $this->message->room_id,
            'user_id'    => $this->message->user_id,
            'body'       => $this->message->body,
            'created_at' => $this->message->created_at?->toISOString(),
        ];
    }
}
