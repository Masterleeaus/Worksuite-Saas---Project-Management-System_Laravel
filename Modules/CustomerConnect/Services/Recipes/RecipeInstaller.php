<?php

namespace Modules\CustomerConnect\Services\Recipes;

use Modules\CustomerConnect\Entities\Campaign;
use Modules\CustomerConnect\Entities\CampaignStep;

class RecipeInstaller
{
    public function install(int $companyId, array $recipe): Campaign
    {
        $campaign = Campaign::create([
            'company_id' => $companyId,
            'name' => $recipe['name'] ?? 'Recipe Campaign',
            'status' => 'draft',
            'starts_at' => null,
            'ends_at' => null,
        ]);

        $order = 1;
        foreach (($recipe['steps'] ?? []) as $step) {
            CampaignStep::create([
                'company_id' => $companyId,
                'campaign_id' => $campaign->id,
                'name' => $step['name'] ?? ('Step ' . $order),
                'channel' => $step['channel'] ?? 'email',
                'content' => $step['content'] ?? '',
                'delay_minutes' => (int)($step['delay_minutes'] ?? 0),
                'sort_order' => $order++,
            ]);
        }

        return $campaign;
    }
}
