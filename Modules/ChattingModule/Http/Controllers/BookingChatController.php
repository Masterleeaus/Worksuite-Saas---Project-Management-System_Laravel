<?php

namespace Modules\ChattingModule\Http\Controllers;

use App\Helper\Reply;
use App\Models\UserChat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\ChattingModule\Models\ChatRoom;

/**
 * Handles booking-specific chat threads that extend the core users_chat table.
 * Messages are stored in users_chat with booking_id and channel='booking'.
 */
class BookingChatController extends Controller
{
    /**
     * Return all messages for a booking thread.
     */
    public function index(Request $request, string $bookingId): JsonResponse
    {
        $messages = UserChat::with('fromUser', 'toUser', 'files')
            ->where('booking_id', $bookingId)
            ->where('is_deleted', false)
            ->orderBy('created_at')
            ->get();

        return Reply::dataOnly(['messages' => $messages]);
    }

    /**
     * Store a new message in a booking thread.
     * Validates and sanitizes input before saving.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'booking_id'   => ['required', 'string', 'max:36'],
            'receiver_id'  => ['required', 'integer'],
            'message'      => ['nullable', 'string', 'max:5000'],
            'message_type' => ['sometimes', 'string', 'in:text,image,file,voice,location'],
            'attachment'   => ['nullable', 'file', 'max:10240'], // 10 MB limit
        ]);

        $messageType = $request->input('message_type', 'text');
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            // Store in the private disk to prevent public access
            $attachmentPath = $request->file('attachment')->store(
                'chat-attachments/' . $request->input('booking_id'),
                'private'
            );
        }

        // Sanitize message body to prevent XSS
        $sanitizedMessage = $request->filled('message')
            ? e($request->input('message'))
            : null;

        $chat = new UserChat();
        $chat->user_one          = user()->id;
        $chat->user_id           = $request->input('receiver_id');
        $chat->from              = user()->id;
        $chat->to                = $request->input('receiver_id');
        $chat->message           = $sanitizedMessage;
        $chat->message_type      = $messageType;
        $chat->attachment_path   = $attachmentPath;
        $chat->booking_id        = $request->input('booking_id');
        $chat->channel           = 'booking';
        $chat->is_read           = false;
        $chat->is_deleted        = false;
        $chat->notification_sent = 0;
        $chat->save();

        return Reply::successWithData('Message sent.', ['chat' => $chat]);
    }

    /**
     * Soft-delete a message (sets is_deleted = true, never hard-deletes from UI).
     */
    public function destroy(int $id): JsonResponse
    {
        $chat = UserChat::findOrFail($id);

        abort_403($chat->from !== user()->id);

        $chat->is_deleted = true;
        $chat->deleted_at = now();
        $chat->save();

        return Reply::success('Message deleted.');
    }

    /**
     * Stream a private attachment for an authorised user.
     * Files are stored on the private disk (not public).
     */
    public function attachment(int $id): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $chat = UserChat::findOrFail($id);

        // Only sender or receiver may download
        abort_403(
            $chat->from !== user()->id && $chat->to !== user()->id
        );

        abort_404(empty($chat->attachment_path));

        return \Illuminate\Support\Facades\Storage::disk('private')
            ->download($chat->attachment_path);
    }

    /**
     * Mark all unread messages in a booking thread as read for the current user.
     */
    public function markRead(Request $request, string $bookingId): JsonResponse
    {
        UserChat::where('booking_id', $bookingId)
            ->where('to', user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return Reply::success('Messages marked as read.');
    }

    /**
     * Count unread messages for the current user across all booking threads.
     */
    public function unreadCount(): JsonResponse
    {
        $count = UserChat::where('to', user()->id)
            ->where('channel', 'booking')
            ->where('is_read', false)
            ->where('is_deleted', false)
            ->count();

        return Reply::dataOnly(['unread_count' => $count]);
    }

    /**
     * Broadcast a message to all members of a ChatRoom.
     * This is a premium feature — the caller should verify subscription.
     */
    public function broadcast(Request $request, int $roomId): JsonResponse
    {
        $request->validate([
            'message'      => ['required', 'string', 'max:5000'],
            'message_type' => ['sometimes', 'string', 'in:text,image,file,voice,location'],
        ]);

        $room = ChatRoom::findOrFail($roomId);

        abort_403($room->type !== 'broadcast' && $room->type !== 'group');

        $sanitizedMessage = e($request->input('message'));
        $messageType = $request->input('message_type', 'text');

        $saved = [];
        foreach ($room->member_ids as $memberId) {
            if ($memberId == user()->id) {
                continue;
            }

            $chat = new UserChat();
            $chat->user_one          = user()->id;
            $chat->user_id           = $memberId;
            $chat->from              = user()->id;
            $chat->to                = $memberId;
            $chat->message           = $sanitizedMessage;
            $chat->message_type      = $messageType;
            $chat->channel           = 'broadcast';
            $chat->is_read           = false;
            $chat->is_deleted        = false;
            $chat->notification_sent = 0;
            $chat->save();

            $saved[] = $chat->id;
        }

        return Reply::successWithData('Broadcast sent.', ['message_ids' => $saved]);
    }
}
