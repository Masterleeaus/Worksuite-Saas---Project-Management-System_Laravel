<?php

namespace Modules\CustomerConnect\Services\Premium;

use Carbon\Carbon;
use Modules\CustomerConnect\Entities\AudienceMember;
use Modules\CustomerConnect\Entities\Contact;

class TemplateVars
{
    /**
     * Build a safe set of merge variables for an audience member.
     *
     * NOTE: Keep this dependency-light for MVP.
     */
    public function forAudienceMember(AudienceMember $m, array $extra = []): array
    {
        $name = $m->name ?: null;
        $email = $m->email ?: null;
        $phone = $m->phone ?: null;

        $contact = $this->resolveContact($m);
        if (!$name && $contact?->display_name) {
            $name = $contact->display_name;
        }

        $business = (string) (config('app.name') ?: 'Our team');

        $base = [
            'name' => $name ?: 'there',
            'email' => $email,
            'phone' => $phone,
            'business' => $business,
            'date' => Carbon::now()->format('Y-m-d'),
            'time' => Carbon::now()->format('H:i'),
            // Common placeholders used by presets
            'link' => $extra['link'] ?? null,
            'amount' => $extra['amount'] ?? null,
            'message' => $extra['message'] ?? null,
        ];

        return array_filter(array_merge($base, $extra), fn($v) => $v !== null);
    }

    protected function resolveContact(AudienceMember $m): ?Contact
    {
        $q = Contact::query()->where('company_id', $m->company_id);

        if (!empty($m->email)) {
            $q->where('email', $m->email);
            return $q->first();
        }

        if (!empty($m->phone)) {
            $digits = preg_replace('/\D+/', '', (string) $m->phone);
            return Contact::query()
                ->where('company_id', $m->company_id)
                ->where(function ($qq) use ($digits) {
                    $qq->where('phone_e164', 'like', '%' . $digits)
                       ->orWhere('whatsapp_e164', 'like', '%' . $digits);
                })->first();
        }

        return null;
    }
}
