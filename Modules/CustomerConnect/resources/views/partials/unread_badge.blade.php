@php
    $unreadCount = 0;
    try {
        $userId = user()->id ?? null;
        $companyId = user()->company_id ?? null;
        if ($userId && $companyId) {
            $unreadCount = app(\Modules\CustomerConnect\Services\Inbox\UnreadCounter::class)->get($userId, $companyId);
        }
    } catch (\Throwable $e) {
        $unreadCount = 0;
    }
@endphp

@if($unreadCount > 0)
    <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
@endif
