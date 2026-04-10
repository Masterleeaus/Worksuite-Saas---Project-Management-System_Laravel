<?php

namespace Modules\TitanReach\Services;

use Modules\TitanReach\Models\ReachCampaign;
use Modules\TitanReach\Models\ReachContact;
use Modules\TitanReach\Models\ReachContactList;
use Modules\TitanReach\Models\ReachSegment;

class CampaignDispatchService
{
    public function __construct(
        protected TwilioSmsService     $smsService,
        protected TwilioWhatsappService $whatsappService,
        protected TelegramService      $telegramService,
        protected TwilioVoiceService   $voiceService,
    ) {}

    /**
     * Dispatch a campaign to all its resolved contacts.
     */
    public function dispatch(ReachCampaign $campaign): void
    {
        $contacts = $this->resolveContacts($campaign->audience_type, $campaign->audience_id);
        $this->dispatchToContacts($contacts, $campaign->channel, (string) $campaign->content);

        $campaign->update(['status' => 'running']);
    }

    /**
     * Send content to a list of contacts via the specified channel.
     *
     * @param  array<ReachContact>  $contacts
     * @return array<int,array<string,mixed>>
     */
    public function dispatchToContacts(array $contacts, string $channel, string $content): array
    {
        $results = [];

        foreach ($contacts as $contact) {
            try {
                $results[] = match ($channel) {
                    'sms'      => $this->smsService->send($contact->phone ?? '', $content),
                    'whatsapp' => $this->whatsappService->send($contact->whatsapp_number ?? $contact->phone ?? '', $content),
                    'telegram' => $this->telegramService->sendMessage($contact->telegram_chat_id ?? '', $content),
                    default    => ['skipped' => true, 'channel' => $channel],
                };
            } catch (\Throwable $e) {
                $results[] = ['error' => $e->getMessage(), 'contact_id' => $contact->id ?? null];
            }
        }

        return $results;
    }

    /**
     * @return array<ReachContact>
     */
    private function resolveContacts(string $audienceType, ?int $audienceId): array
    {
        if ($audienceType === 'contact_list' && $audienceId) {
            $list = ReachContactList::with('contacts')->find($audienceId);
            return $list ? $list->contacts->all() : [];
        }

        if ($audienceType === 'segment' && $audienceId) {
            // Simple implementation: return all contacts for the segment's company_id.
            $segment = ReachSegment::find($audienceId);
            if (!$segment) {
                return [];
            }
            return ReachContact::where('company_id', $segment->company_id)
                ->where('opted_out', false)
                ->get()
                ->all();
        }

        return [];
    }
}
