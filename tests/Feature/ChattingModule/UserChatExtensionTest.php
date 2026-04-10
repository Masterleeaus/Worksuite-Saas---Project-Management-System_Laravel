<?php

namespace Tests\Feature\ChattingModule;

use App\Models\User;
use App\Models\UserChat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ChattingModule\Models\ChatRoom;
use Tests\TestCase;

/**
 * Feature tests for the ChattingModule FSM integration layer.
 *
 * Covers:
 *  - Message saved to users_chat with the correct booking_id
 *  - Broadcast reaches all members of a ChatRoom
 *  - Unread count calculates correctly
 */
class UserChatExtensionTest extends TestCase
{
    use RefreshDatabase;

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function makeUser(array $attrs = []): User
    {
        return User::factory()->create($attrs);
    }

    // -----------------------------------------------------------------------
    // Test: new FSM columns exist on users_chat
    // -----------------------------------------------------------------------

    /** @test */
    public function users_chat_table_has_fsm_columns(): void
    {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users_chat');

        $this->assertContains('message_type', $columns);
        $this->assertContains('attachment_path', $columns);
        $this->assertContains('is_read', $columns);
        $this->assertContains('read_at', $columns);
        $this->assertContains('booking_id', $columns);
        $this->assertContains('channel', $columns);
        $this->assertContains('is_deleted', $columns);
        $this->assertContains('deleted_at', $columns);
    }

    // -----------------------------------------------------------------------
    // Test: message saved to users_chat with correct booking_id
    // -----------------------------------------------------------------------

    /** @test */
    public function message_is_saved_with_correct_booking_id(): void
    {
        $sender   = $this->makeUser();
        $receiver = $this->makeUser();
        $bookingId = \Illuminate\Support\Str::uuid()->toString();

        $chat = UserChat::create([
            'user_one'          => $sender->id,
            'user_id'           => $receiver->id,
            'from'              => $sender->id,
            'to'                => $receiver->id,
            'message'           => 'Hello from booking test',
            'message_type'      => 'text',
            'booking_id'        => $bookingId,
            'channel'           => 'booking',
            'is_read'           => false,
            'is_deleted'        => false,
            'notification_sent' => 0,
        ]);

        $this->assertDatabaseHas('users_chat', [
            'id'         => $chat->id,
            'booking_id' => $bookingId,
            'channel'    => 'booking',
            'from'       => $sender->id,
        ]);
    }

    // -----------------------------------------------------------------------
    // Test: broadcast reaches all members of a ChatRoom
    // -----------------------------------------------------------------------

    /** @test */
    public function broadcast_creates_messages_for_all_room_members(): void
    {
        $sender  = $this->makeUser();
        $member1 = $this->makeUser();
        $member2 = $this->makeUser();

        $room = ChatRoom::create([
            'company_id' => null,
            'name'       => 'Zone A Cleaners',
            'type'       => 'broadcast',
            'member_ids' => [$sender->id, $member1->id, $member2->id],
            'created_by' => $sender->id,
        ]);

        $message = 'All cleaners to Zone A immediately.';

        foreach ($room->member_ids as $memberId) {
            if ($memberId === $sender->id) {
                continue;
            }

            UserChat::create([
                'user_one'          => $sender->id,
                'user_id'           => $memberId,
                'from'              => $sender->id,
                'to'                => $memberId,
                'message'           => $message,
                'message_type'      => 'text',
                'channel'           => 'broadcast',
                'is_read'           => false,
                'is_deleted'        => false,
                'notification_sent' => 0,
            ]);
        }

        $this->assertDatabaseHas('users_chat', ['from' => $sender->id, 'to' => $member1->id, 'channel' => 'broadcast']);
        $this->assertDatabaseHas('users_chat', ['from' => $sender->id, 'to' => $member2->id, 'channel' => 'broadcast']);
        $this->assertDatabaseMissing('users_chat', ['from' => $sender->id, 'to' => $sender->id, 'channel' => 'broadcast']);
    }

    // -----------------------------------------------------------------------
    // Test: unread count calculates correctly
    // -----------------------------------------------------------------------

    /** @test */
    public function unread_count_calculates_correctly(): void
    {
        $sender   = $this->makeUser();
        $receiver = $this->makeUser();

        // 3 unread messages
        foreach (range(1, 3) as $i) {
            UserChat::create([
                'user_one'          => $sender->id,
                'user_id'           => $receiver->id,
                'from'              => $sender->id,
                'to'                => $receiver->id,
                'message'           => "Message {$i}",
                'message_type'      => 'text',
                'channel'           => 'booking',
                'is_read'           => false,
                'is_deleted'        => false,
                'notification_sent' => 0,
            ]);
        }

        // 1 already-read message
        UserChat::create([
            'user_one'          => $sender->id,
            'user_id'           => $receiver->id,
            'from'              => $sender->id,
            'to'                => $receiver->id,
            'message'           => 'Read message',
            'message_type'      => 'text',
            'channel'           => 'booking',
            'is_read'           => true,
            'read_at'           => now(),
            'is_deleted'        => false,
            'notification_sent' => 0,
        ]);

        $unreadCount = UserChat::where('to', $receiver->id)
            ->where('channel', 'booking')
            ->where('is_read', false)
            ->where('is_deleted', false)
            ->count();

        $this->assertEquals(3, $unreadCount);
    }

    // -----------------------------------------------------------------------
    // Test: soft-deleted messages excluded from unread count
    // -----------------------------------------------------------------------

    /** @test */
    public function soft_deleted_messages_are_excluded_from_unread_count(): void
    {
        $sender   = $this->makeUser();
        $receiver = $this->makeUser();

        // 1 unread active + 1 soft-deleted unread
        UserChat::create([
            'user_one'          => $sender->id,
            'user_id'           => $receiver->id,
            'from'              => $sender->id,
            'to'                => $receiver->id,
            'message'           => 'Active message',
            'message_type'      => 'text',
            'channel'           => 'direct',
            'is_read'           => false,
            'is_deleted'        => false,
            'notification_sent' => 0,
        ]);

        UserChat::create([
            'user_one'          => $sender->id,
            'user_id'           => $receiver->id,
            'from'              => $sender->id,
            'to'                => $receiver->id,
            'message'           => 'Deleted message',
            'message_type'      => 'text',
            'channel'           => 'direct',
            'is_read'           => false,
            'is_deleted'        => true,
            'deleted_at'        => now(),
            'notification_sent' => 0,
        ]);

        $unreadCount = UserChat::where('to', $receiver->id)
            ->where('is_read', false)
            ->where('is_deleted', false)
            ->count();

        $this->assertEquals(1, $unreadCount);
    }

    // -----------------------------------------------------------------------
    // Test: chat_rooms table created and ChatRoom model works
    // -----------------------------------------------------------------------

    /** @test */
    public function chat_rooms_table_exists_and_model_persists(): void
    {
        $creator = $this->makeUser();

        $room = ChatRoom::create([
            'company_id' => null,
            'name'       => 'Booking Chat Room',
            'type'       => 'booking',
            'booking_id' => \Illuminate\Support\Str::uuid()->toString(),
            'member_ids' => [$creator->id],
            'created_by' => $creator->id,
        ]);

        $this->assertDatabaseHas('chat_rooms', ['id' => $room->id, 'type' => 'booking']);
        $this->assertIsArray($room->member_ids);
    }
}
