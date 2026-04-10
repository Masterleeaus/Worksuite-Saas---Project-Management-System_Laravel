<?php

namespace Modules\TitanReach\Services;

use Modules\TitanReach\Models\ReachConversation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InboxAggregatorService
{
    /**
     * Return paginated conversations for a company with optional filters.
     *
     * @param  array<string,mixed>  $filters  Keys: channel, status, search, assigned_to
     */
    public function getConversations(int $companyId, array $filters = []): LengthAwarePaginator
    {
        $query = ReachConversation::with('contact')
            ->where('company_id', $companyId);

        if (!empty($filters['channel'])) {
            $query->where('channel', $filters['channel']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($search) {
                $q->where('last_message', 'like', $search)
                  ->orWhereHas('contact', fn ($cq) => $cq->where('name', 'like', $search)
                      ->orWhere('phone', 'like', $search));
            });
        }

        return $query->orderByDesc('updated_at')->paginate(20);
    }

    public function getConversation(int $id): ?ReachConversation
    {
        return ReachConversation::with(['contact', 'messages'])->find($id);
    }

    public function markAsRead(int $conversationId): void
    {
        ReachConversation::where('id', $conversationId)->update(['unread_count' => 0]);
    }

    public function assignTo(int $conversationId, int $userId): void
    {
        ReachConversation::where('id', $conversationId)->update(['assigned_to' => $userId]);
    }

    public function updateStatus(int $conversationId, string $status): void
    {
        ReachConversation::where('id', $conversationId)->update(['status' => $status]);
    }
}
