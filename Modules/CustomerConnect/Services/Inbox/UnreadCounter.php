<?php

namespace Modules\CustomerConnect\Services\Inbox;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UnreadCounter
{
    public function get(int $userId, int $companyId): int
    {
        $key = $this->cacheKey($userId, $companyId);
        $ttl = (int) config('customerconnect.inbox.unread_cache_ttl', 60);

        return Cache::remember($key, $ttl, function () use ($userId, $companyId) {
            try {
                // unread = threads where last_message_at > last_read_at (or no read row)
                $threads = DB::table('customerconnect_threads')
                    ->where('company_id', $companyId);

                $count = $threads->where(function($q) use ($userId) {
                        $q->whereNotExists(function($sub) use ($userId) {
                            $sub->select(DB::raw(1))
                                ->from('customerconnect_thread_reads')
                                ->whereColumn('customerconnect_thread_reads.thread_id', 'customerconnect_threads.id')
                                ->where('customerconnect_thread_reads.user_id', $userId);
                        })
                        ->orWhereExists(function($sub) use ($userId) {
                            $sub->select(DB::raw(1))
                                ->from('customerconnect_thread_reads')
                                ->whereColumn('customerconnect_thread_reads.thread_id', 'customerconnect_threads.id')
                                ->where('customerconnect_thread_reads.user_id', $userId)
                                ->whereColumn('customerconnect_thread_reads.last_read_at', '<', 'customerconnect_threads.last_message_at');
                        });
                    })
                    ->count();

                return (int) $count;
            } catch (\Throwable $e) {
                Log::warning('[CustomerConnect] UnreadCounter failed', ['e' => $e->getMessage()]);
                return 0;
            }
        });
    }

    public function forget(int $userId, int $companyId): void
    {
        Cache::forget($this->cacheKey($userId, $companyId));
    }

    private function cacheKey(int $userId, int $companyId): string
    {
        return "customerconnect:unread:{$companyId}:{$userId}";
    }
}
