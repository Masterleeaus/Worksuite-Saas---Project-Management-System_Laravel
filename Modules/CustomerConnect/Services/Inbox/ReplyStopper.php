<?php

namespace Modules\CustomerConnect\Services\Inbox;

use Modules\CustomerConnect\Entities\Campaign;
use Modules\CustomerConnect\Entities\CampaignRun;
use Modules\CustomerConnect\Entities\Delivery;

/**
 * When a customer replies inbound, optionally stop future queued deliveries for campaigns that opt-in.
 * This is NOT conversation automation; it's a safety guard to prevent spam after a reply.
 */
class ReplyStopper
{
    public function stopQueuedDeliveriesOnReply(int $companyId, string $channel, ?string $fromAddress): int
    {
        if (!$fromAddress) return 0;

        $from = $this->normalize($channel, $fromAddress);

        // Find queued deliveries matching recipient address (delivery.to is the customer destination)
        $deliveries = Delivery::query()
            ->where('company_id', $companyId)
            ->where('status', 'queued')
            ->where('channel', $channel)
            ->whereNotNull('to')
            ->where('to', $from)
            ->get();

        if ($deliveries->isEmpty()) {
            return 0;
        }

        $runIds = $deliveries->pluck('run_id')->unique()->all();
        $runs = CampaignRun::query()->whereIn('id', $runIds)->get()->keyBy('id');

        $skipped = 0;

        foreach ($deliveries as $d) {
            $run = $runs->get($d->run_id);
            if (!$run) continue;

            $campaign = Campaign::query()->find($run->campaign_id);
            if (!$campaign || !$campaign->stop_on_reply) continue;

            $d->status = 'skipped';
            $d->error = 'Stopped on inbound reply';
            $d->save();
            $skipped++;
        }

        return $skipped;
    }

    private function normalize(string $channel, string $addr): string
    {
        $a = trim($addr);
        if ($channel === 'whatsapp') {
            // stored as whatsapp:+E164
            if (stripos($a, 'whatsapp:') !== 0) {
                $a = 'whatsapp:' . $a;
            }
        }
        return $a;
    }
}
