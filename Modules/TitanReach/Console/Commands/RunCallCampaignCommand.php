<?php

namespace Modules\TitanReach\Console\Commands;

use Illuminate\Console\Command;
use Modules\TitanReach\Models\ReachCampaign;
use Modules\TitanReach\Models\ReachCallLog;
use Modules\TitanReach\Models\ReachContactList;
use Modules\TitanReach\Models\ReachContact;
use Modules\TitanReach\Models\ReachSegment;
use Modules\TitanReach\Services\TwilioVoiceService;

class RunCallCampaignCommand extends Command
{
    protected $signature   = 'reach:run-call-campaign';
    protected $description = 'Run scheduled outbound call campaigns';

    public function handle(TwilioVoiceService $voice): void
    {
        $campaigns = ReachCampaign::where('channel', 'call')
            ->where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($campaigns->isEmpty()) {
            $this->info('No scheduled call campaigns to run.');
            return;
        }

        foreach ($campaigns as $campaign) {
            $this->info("Running call campaign: {$campaign->name} (#{$campaign->id})");

            $contacts  = $this->resolveContacts($campaign);
            $twimlUrl  = route('titanreach.webhooks.voice.twiml', ['script' => $campaign->call_script ?? 'Hello from TitanReach.']);
            $initiated = 0;
            $errors    = 0;

            foreach ($contacts as $contact) {
                if (empty($contact->phone)) {
                    continue;
                }

                try {
                    $result = $voice->makeCall($contact->phone, $twimlUrl);

                    ReachCallLog::create([
                        'company_id'       => $campaign->company_id,
                        'call_campaign_id' => $campaign->id,
                        'contact_id'       => $contact->id,
                        'call_sid'         => $result['sid'] ?? null,
                        'direction'        => 'outbound',
                        'from_number'      => config('titanreach.twilio.from_sms_number', ''),
                        'to_number'        => $contact->phone,
                        'status'           => $result['status'] ?? 'initiated',
                        'called_at'        => now(),
                    ]);

                    $initiated++;
                } catch (\Throwable $e) {
                    $this->error("  Failed for {$contact->phone}: {$e->getMessage()}");
                    $errors++;
                }
            }

            $campaign->update([
                'status' => 'running',
                'stats'  => array_merge((array) $campaign->stats, [
                    'initiated' => $initiated,
                    'errors'    => $errors,
                ]),
            ]);

            $this->info("  Initiated: {$initiated}, Errors: {$errors}");
        }
    }

    /**
     * @return array<ReachContact>
     */
    private function resolveContacts(ReachCampaign $campaign): array
    {
        if ($campaign->audience_type === 'contact_list' && $campaign->audience_id) {
            $list = ReachContactList::with('contacts')->find($campaign->audience_id);
            return $list ? $list->contacts->all() : [];
        }

        if ($campaign->audience_type === 'segment' && $campaign->audience_id) {
            $segment = ReachSegment::find($campaign->audience_id);
            if (!$segment) {
                return [];
            }
            return ReachContact::where('company_id', $segment->company_id)
                ->where('opted_out', false)
                ->whereNotNull('phone')
                ->get()
                ->all();
        }

        return [];
    }
}
