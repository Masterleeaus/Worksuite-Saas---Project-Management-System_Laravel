<?php

namespace Modules\CustomerConnect\Services\Safety;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\CustomerConnect\Entities\AudienceMember;
use Modules\CustomerConnect\Entities\Contact;
use Modules\CustomerConnect\Entities\Message;
use Modules\CustomerConnect\Entities\Thread;

/**
 * Filters audience members for "smart" campaign runs.
 *
 * MVP rules:
 * - Exclude members that have had any message activity in the last N days.
 * - Resolve by Contact matching email/phone.
 */
class AudienceExclusions
{
    /**
     * @param Collection<int, AudienceMember> $members
     * @return Collection<int, AudienceMember>
     */
    public function excludeRecentActivity(Collection $members, int $days): Collection
    {
        if ($days <= 0) {
            return $members;
        }

        $cutoff = Carbon::now()->subDays($days);

        return $members->filter(function (AudienceMember $m) use ($cutoff) {
            $contact = $this->resolveContact($m);
            if (!$contact) {
                return true;
            }

            $threadIds = Thread::query()
                ->where('company_id', $m->company_id)
                ->where('contact_id', $contact->id)
                ->pluck('id');

            if ($threadIds->isEmpty()) {
                return true;
            }

            $recent = Message::query()
                ->where('company_id', $m->company_id)
                ->whereIn('thread_id', $threadIds)
                ->where('created_at', '>=', $cutoff)
                ->exists();

            return !$recent;
        })->values();
    }

    protected function resolveContact(AudienceMember $m): ?Contact
    {
        if (!empty($m->email)) {
            return Contact::query()
                ->where('company_id', $m->company_id)
                ->where('email', $m->email)
                ->first();
        }

        if (!empty($m->phone)) {
            $digits = preg_replace('/\D+/', '', (string) $m->phone);
            return Contact::query()
                ->where('company_id', $m->company_id)
                ->where(function ($q) use ($digits) {
                    $q->where('phone_e164', 'like', '%' . $digits)
                      ->orWhere('whatsapp_e164', 'like', '%' . $digits);
                })->first();
        }

        return null;
    }
}
