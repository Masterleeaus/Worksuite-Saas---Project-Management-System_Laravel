<?php

namespace Modules\TitanReach\Console\Commands;

use Illuminate\Console\Command;
use Modules\TitanReach\Models\ReachCampaign;
use Modules\TitanReach\Models\ReachContactList;
use Modules\TitanReach\Models\ReachContact;
use Modules\TitanReach\Models\ReachSegment;
use Modules\TitanReach\Services\CampaignDispatchService;

class RunSmsCampaignCommand extends Command
{
    protected $signature   = 'reach:run-sms-campaign';
    protected $description = 'Run scheduled SMS campaigns';

    public function handle(CampaignDispatchService $dispatcher): void
    {
        $campaigns = ReachCampaign::where('channel', 'sms')
            ->where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($campaigns->isEmpty()) {
            $this->info('No scheduled SMS campaigns to run.');
            return;
        }

        foreach ($campaigns as $campaign) {
            $this->info("Running campaign: {$campaign->name} (#{$campaign->id})");

            try {
                $contacts = $this->resolveContacts($campaign);
                $results  = $dispatcher->dispatchToContacts($contacts, 'sms', (string) $campaign->content);

                $sent   = count(array_filter($results, fn ($r) => !isset($r['error'])));
                $errors = count(array_filter($results, fn ($r) => isset($r['error'])));

                $campaign->update([
                    'status' => 'running',
                    'stats'  => array_merge((array) $campaign->stats, [
                        'sent'   => $sent,
                        'errors' => $errors,
                    ]),
                ]);

                $this->info("  Sent: {$sent}, Errors: {$errors}");
            } catch (\Throwable $e) {
                $this->error("  Failed: {$e->getMessage()}");
            }
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
