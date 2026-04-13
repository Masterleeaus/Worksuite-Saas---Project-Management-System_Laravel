<?php

namespace Modules\TitanTalk\Services;

use App\Helper\Files;
use App\Models\User;
use App\Models\UserChat;
use Illuminate\Http\UploadedFile;
use Modules\TitanTalk\Models\TitanTalkMessage;
use Modules\TitanTalk\Models\TitanTalkMessageFile;
use Modules\TitanTalk\Models\TitanTalkRoom;
use Modules\TitanTalk\Models\TitanTalkRoomMember;

/**
 * TitanTalkService
 *
 * Central integration service for TitanTalk.
 * Bridges to Worksuite's built-in DM stack (UserChat) and ChattingModule (ChatRoom)
 * for consistent single-source-of-truth messaging.
 */
class TitanTalkService
{
    // -------------------------------------------------------------------------
    // DM Bridge (reuse Worksuite UserChat for 1:1 direct messages)
    // -------------------------------------------------------------------------

    /**
     * Resolve or create a TitanTalk DM room backed by the Worksuite UserChat system.
     *
     * TitanTalk DM rooms use type='dm' and store a canonical slug dm_{u1}_{u2} so the
     * same pair never gets two rooms. The actual messages are stored in titan_talk_messages,
     * and callers may optionally also mirror to UserChat via mirrorToUserChat().
     */
    public function resolveDmRoom(int $fromUserId, int $toUserId, int $companyId): TitanTalkRoom
    {
        // Canonical order: lower ID first to avoid duplicate rooms
        [$u1, $u2] = $fromUserId < $toUserId
            ? [$fromUserId, $toUserId]
            : [$toUserId, $fromUserId];

        $slug = "dm_{$u1}_{$u2}";

        $room = TitanTalkRoom::where('company_id', $companyId)
            ->where('type', 'dm')
            ->where('slug', $slug)
            ->first();

        if (!$room) {
            $user1 = User::find($u1);
            $user2 = User::find($u2);
            $name  = ($user1?->name ?? "User {$u1}") . ' & ' . ($user2?->name ?? "User {$u2}");

            $room = TitanTalkRoom::create([
                'company_id'     => $companyId,
                'name'           => $name,
                'slug'           => $slug,
                'type'           => 'dm',
                'reference_type' => 'user_chat_dm',
                'reference_id'   => $u2,
                'created_by'     => $fromUserId,
            ]);

            TitanTalkRoomMember::firstOrCreate(
                ['room_id' => $room->id, 'user_id' => $u1],
                ['role' => 'member']
            );
            TitanTalkRoomMember::firstOrCreate(
                ['room_id' => $room->id, 'user_id' => $u2],
                ['role' => 'member']
            );
        }

        return $room;
    }

    /**
     * Mirror a TitanTalk DM message into the Worksuite UserChat inbox so both
     * TitanTalk and /messages stay in sync.
     *
     * IMPORTANT: We use UserChat::withoutObservers() so that NewChatObserver does
     * NOT fire its own NewChatEvent / NewChat notification / NewMessage broadcast.
     * TitanTalk's own TitanTalkNewMessage notification is the single source of truth
     * for DM notifications. The UserChat record is created only so the /messages
     * inbox shows the conversation — not to trigger a second notification chain.
     */
    public function mirrorToUserChat(TitanTalkMessage $message, int $toUserId): ?UserChat
    {
        try {
            // withoutObservers prevents NewChatObserver::created() from firing,
            // which eliminates the duplicate NewChat notification + NewMessage broadcast.
            return UserChat::withoutObservers(function () use ($message, $toUserId) {
                $chat = new UserChat();
                $chat->user_one          = $message->user_id;
                $chat->user_id           = $toUserId;
                $chat->from              = $message->user_id;
                $chat->to                = $toUserId;
                $chat->message           = $message->body;
                $chat->notification_sent = 1; // Suppress any future notification queries
                $chat->company_id        = $message->company_id;
                $chat->save();

                return $chat;
            });
        } catch (\Throwable) {
            return null;
        }
    }

    // -------------------------------------------------------------------------
    // File upload (reuses app/Helper/Files::uploadLocalOrS3)
    // -------------------------------------------------------------------------

    /**
     * Upload a file attachment for a TitanTalk message.
     * Uses Worksuite's Files::uploadLocalOrS3 for consistent storage (local/S3/DO/Wasabi).
     */
    public function attachFile(TitanTalkMessage $message, UploadedFile $file): TitanTalkMessageFile
    {
        $dir      = 'titan-talk/' . $message->room_id;
        $hashname = Files::uploadLocalOrS3($file, $dir);

        return $message->files()->create([
            'filename'          => $hashname,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type'         => $file->getMimeType() ?? 'application/octet-stream',
            'file_size'         => $file->getSize(),
            'disk'              => config('filesystems.default', 'local'),
        ]);
    }

    // -------------------------------------------------------------------------
    // Mention parsing (mirrors NewChatObserver mention logic)
    // -------------------------------------------------------------------------

    /**
     * Parse @mentions from message body and return matched User instances.
     * Mirrors the UserChat mention flow in app/Observers/NewChatObserver.php.
     *
     * @return \Illuminate\Support\Collection<int, User>
     */
    public function parseMentions(string $body, int $companyId): \Illuminate\Support\Collection
    {
        preg_match_all('/@([a-zA-Z0-9_.]+)/', $body, $matches);

        if (empty($matches[1])) {
            return collect();
        }

        $slugs = collect($matches[1])->unique()->values();

        // Heuristic: match @token against user names with a substring search.
        // This is the same best-effort approach used by NewChatObserver for DM mentions.
        // For a stricter match, store a separate username/slug field on users and query it.
        return User::where('company_id', $companyId)
            ->where(function ($q) use ($slugs) {
                foreach ($slugs as $slug) {
                    $q->orWhere('name', 'like', '%' . addcslashes($slug, '%_\\') . '%');
                }
            })
            ->get();
    }

    // -------------------------------------------------------------------------
    // Auto-room creation (observer-triggered, safe/idempotent)
    // -------------------------------------------------------------------------

    /**
     * Safely create a TitanTalk room for a business object (booking, job, issue, project).
     * Returns the existing room if one already exists for this reference.
     * Returns null if auto_create_rooms config is disabled for the given type key.
     */
    public function autoCreateRoom(
        string $type,
        string $name,
        int $companyId,
        int $createdBy,
        int $referenceId,
        string $referenceType
    ): ?TitanTalkRoom {
        $autoConfig = config('titantalk.auto_create_rooms', []);

        if (isset($autoConfig[$type]) && $autoConfig[$type] === false) {
            return null;
        }

        // Idempotent: return existing room if already created for this reference
        $existing = TitanTalkRoom::where('company_id', $companyId)
            ->where('reference_id', $referenceId)
            ->where('reference_type', $referenceType)
            ->first();

        if ($existing) {
            return $existing;
        }

        $baseSlug = \Illuminate\Support\Str::slug("{$type}-{$referenceId}");
        $slug     = $baseSlug;
        $i        = 2;
        while (TitanTalkRoom::where('company_id', $companyId)->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$i}";
            $i++;
        }

        $room = TitanTalkRoom::create([
            'company_id'     => $companyId,
            'name'           => $name,
            'slug'           => $slug,
            'type'           => $type,
            'reference_id'   => $referenceId,
            'reference_type' => $referenceType,
            'created_by'     => $createdBy,
        ]);

        TitanTalkRoomMember::firstOrCreate(
            ['room_id' => $room->id, 'user_id' => $createdBy],
            ['role' => 'owner']
        );

        return $room;
    }

    // -------------------------------------------------------------------------
    // SMS escalation bridge (optional, via Sms module)
    // -------------------------------------------------------------------------

    /**
     * Send an urgent SMS via the Sms module when installed and configured.
     * Uses Modules\Sms\Services\CleaningNotificationService::send() with the
     * titan-talk-mention slug. Fails silently if Sms module is not present.
     */
    public function sendSmsEscalation(User $notifiable, string $messageText): void
    {
        if (!class_exists(\Modules\Sms\Services\CleaningNotificationService::class)) {
            return;
        }

        if (!class_exists(\Modules\Sms\Enums\SmsNotificationSlug::class)) {
            return;
        }

        try {
            $slug = \Modules\Sms\Enums\SmsNotificationSlug::from('titan-talk-mention');
        } catch (\ValueError) {
            return; // Slug not registered — skip
        }

        try {
            app(\Modules\Sms\Services\CleaningNotificationService::class)
                ->send($notifiable, $slug, $messageText);
        } catch (\Throwable) {
            // SMS escalation is optional; never break TitanTalk
        }
    }

    // -------------------------------------------------------------------------
    // Communication module bridge
    // -------------------------------------------------------------------------

    /**
     * Log a TitanTalk event into the Communications history table.
     * Uses Modules\Communication\Entities\Communication for the audit trail.
     * Fails silently if Communication module is not installed.
     */
    public function logToCommunication(
        int $companyId,
        ?int $fromUserId,
        ?int $toUserId,
        string $subject,
        string $body,
        ?int $roomId = null
    ): void {
        if (!class_exists(\Modules\Communication\Entities\Communication::class)) {
            return;
        }

        try {
            $data = [
                'company_id'   => $companyId,
                'type'         => 'chat',
                'from_user_id' => $fromUserId,
                'to_user_id'   => $toUserId,
                'subject'      => $subject,
                'body'         => $body,
                'status'       => 'sent',
                'sent_at'      => now(),
            ];

            // Use titan_talk_room_id if the column exists (added by migration 000010).
            // Fall back to booking_id for backward compatibility with un-migrated installs.
            if ($roomId !== null) {
                $commsTable = (new \Modules\Communication\Entities\Communication())->getTable();
                if (\Illuminate\Support\Facades\Schema::hasColumn($commsTable, 'titan_talk_room_id')) {
                    $data['titan_talk_room_id'] = $roomId;
                } else {
                    $data['booking_id'] = (string) $roomId;
                }
            }

            \Modules\Communication\Entities\Communication::create($data);
        } catch (\Throwable) {
            // Communication module may not be migrated yet; fail silently
        }
    }
}
