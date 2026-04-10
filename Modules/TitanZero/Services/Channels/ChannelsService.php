<?php

namespace Modules\TitanZero\Services\Channels;

use Modules\TitanZero\Entities\TitanZeroChannel;
use Illuminate\Support\Arr;

class ChannelsService
{
    public function defaults(): array
    {
        return [
            [
                'key' => 'web',
                'label' => 'Web (Floating Widget)',
                'enabled' => true,
                'config' => [
                    'mode' => 'widget',
                ],
            ],
            [
                'key' => 'sms',
                'label' => 'SMS (Twilio)',
                'enabled' => false,
                'config' => [
                    'from_number' => env('TWILIO_FROM', ''),
                ],
            ],
            [
                'key' => 'whatsapp',
                'label' => 'WhatsApp (Twilio)',
                'enabled' => false,
                'config' => [
                    'from_number' => env('TWILIO_WHATSAPP_FROM', ''),
                ],
            ],
            [
                'key' => 'email',
                'label' => 'Email',
                'enabled' => false,
                'config' => [
                    'from_address' => env('MAIL_FROM_ADDRESS', ''),
                    'from_name' => env('MAIL_FROM_NAME', ''),
                ],
            ],
            [
                'key' => 'voice',
                'label' => 'Voice (Twilio / IVR)',
                'enabled' => false,
                'config' => [
                    'from_number' => env('TWILIO_VOICE_FROM', ''),
                ],
            ],
        ];
    }

    public function companyId(): ?int
    {
        $u = auth()->user();
        if (!$u) return null;
        // Worksuite often has company_id; fallback to user_id for single-tenant cases
        return (int) ($u->company_id ?? $u->id ?? 0);
    }

    public function userId(): ?int
    {
        return auth()->id() ? (int) auth()->id() : null;
    }

    public function ensureDefaults(): void
    {
        $companyId = $this->companyId();
        $userId = $this->userId();

        foreach ($this->defaults() as $def) {
            TitanZeroChannel::query()->firstOrCreate(
                ['company_id' => $companyId, 'key' => $def['key']],
                [
                    'user_id' => $userId,
                    'label' => $def['label'],
                    'enabled' => (bool) $def['enabled'],
                    'config' => $def['config'] ?? [],
                    'health' => $this->computeHealth($def['key']),
                ]
            );
        }
    }

    public function list(): array
    {
        $this->ensureDefaults();

        $companyId = $this->companyId();

        $rows = TitanZeroChannel::query()
            ->where('company_id', $companyId)
            ->orderByRaw("FIELD(`key`,'web','sms','whatsapp','email','voice')")
            ->get();

        // Refresh health snapshot each load (cheap checks only)
        foreach ($rows as $row) {
            $row->health = $this->computeHealth($row->key);
            $row->last_checked_at = now();
            $row->save();
        }

        return $rows->toArray();
    }

    public function updateFromRequest(array $payload): void
    {
        $companyId = $this->companyId();
        $userId = $this->userId();

        foreach ($payload as $key => $data) {
            $row = TitanZeroChannel::query()
                ->where('company_id', $companyId)
                ->where('key', $key)
                ->first();

            if (!$row) continue;

            $row->user_id = $userId;
            $row->enabled = (bool) Arr::get($data, 'enabled', false);

            $config = $row->config ?? [];
            $incomingConfig = Arr::get($data, 'config', []);
            if (is_array($incomingConfig)) {
                $config = array_merge($config, $incomingConfig);
            }
            $row->config = $config;

            $row->health = $this->computeHealth($key);
            $row->last_checked_at = now();
            $row->save();
        }
    }

    public function computeHealth(string $key): array
    {
        // These are lightweight checks only (env/config presence),
        // not external network calls.
        $health = [
            'status' => 'unknown',
            'notes' => [],
        ];

        if ($key === 'web') {
            $health['status'] = 'ok';
            $health['notes'][] = 'Widget available.';
            return $health;
        }

        if (in_array($key, ['sms','whatsapp','voice'], true)) {
            $sid = env('TWILIO_SID', env('TWILIO_ACCOUNT_SID', ''));
            $token = env('TWILIO_TOKEN', env('TWILIO_AUTH_TOKEN', ''));
            if ($sid && $token) {
                $health['status'] = 'ok';
                $health['notes'][] = 'Twilio credentials present.';
            } else {
                $health['status'] = 'needs_config';
                $health['notes'][] = 'Missing TWILIO_SID/TWILIO_TOKEN (or TWILIO_ACCOUNT_SID/TWILIO_AUTH_TOKEN).';
            }
            return $health;
        }

        if ($key === 'email') {
            $driver = env('MAIL_MAILER', env('MAIL_DRIVER', ''));
            $host = env('MAIL_HOST', '');
            if ($driver && $host) {
                $health['status'] = 'ok';
                $health['notes'][] = 'Mail configuration present.';
            } else {
                $health['status'] = 'needs_config';
                $health['notes'][] = 'Missing MAIL_MAILER/MAIL_HOST.';
            }
            return $health;
        }

        return $health;
    }
}
