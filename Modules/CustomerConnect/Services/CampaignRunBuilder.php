<?php

namespace Modules\CustomerConnect\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\CustomerConnect\Entities\Audience;
use Modules\CustomerConnect\Entities\AudienceMember;
use Modules\CustomerConnect\Entities\Campaign;
use Modules\CustomerConnect\Entities\CampaignRun;
use Modules\CustomerConnect\Entities\CampaignStep;
use Modules\CustomerConnect\Entities\Delivery;
use Modules\CustomerConnect\Enums\RunStatus;

class CampaignRunBuilder
{
    public function __construct(
        protected DedupeService $dedupeService
    ) {}

    /**
     * Build deliveries for a run (one delivery per audience member per SEND step).
     */
    public function build(CampaignRun $run): CampaignRun
    {
        $run->loadMissing(['campaign', 'audience', 'campaign.steps']);

        $campaign = $run->campaign;
        if (!$campaign) {
            return $run;
        }

        $audience = $run->audience ?: $this->resolveDefaultAudience($campaign);
        if (!$audience) {
            return $run;
        }

        $members = AudienceMember::query()
            ->where('audience_id', $audience->id)
            ->get();

        $members = $this->dedupeService->dedupe($members);

        $steps = $campaign->steps()
            ->orderBy('position')
            ->get();

        DB::transaction(function () use ($run, $members, $steps) {
            foreach ($steps as $step) {
                if ($step->type !== 'send') {
                    continue;
                }
                foreach ($members as $member) {
                    Delivery::query()->firstOrCreate([
                        'run_id' => $run->id,
                        'step_id' => $step->id,
                        'audience_member_id' => $member->id,
                        'company_id' => $run->company_id,
                        'channel' => (string)$step->channel,
                    ], [
                        'to' => null,
                        'subject' => $step->subject,
                        'body' => $step->body,
                        'status' => 'queued',
                        'scheduled_for' => $this->calculateDeliverySchedule($run->scheduled_at, $step),
                    ]);
                }
            }
        });

        return $run;
    }

    protected function resolveDefaultAudience(Campaign $campaign): ?Audience
    {
        $settings = (array)($campaign->settings ?? []);
        $audienceId = $settings['default_audience_id'] ?? null;
        if (!$audienceId) {
            return null;
        }
        return Audience::find($audienceId);
    }

    protected function calculateDeliverySchedule($runScheduledAt, CampaignStep $step): ?Carbon
    {
        $base = $runScheduledAt ? Carbon::parse($runScheduledAt) : Carbon::now();
        $delay = (int)($step->delay_minutes ?? 0);
        return $base->copy()->addMinutes($delay);
    }
}
