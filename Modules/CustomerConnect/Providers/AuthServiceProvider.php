<?php

namespace Modules\CustomerConnect\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\CustomerConnect\Entities\Campaign;
use Modules\CustomerConnect\Entities\Audience;
use Modules\CustomerConnect\Entities\CampaignRun;
use Modules\CustomerConnect\Policies\CampaignPolicy;
use Modules\CustomerConnect\Policies\AudiencePolicy;
use Modules\CustomerConnect\Policies\RunPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Campaign::class => CampaignPolicy::class,
        Audience::class => AudiencePolicy::class,
        CampaignRun::class => RunPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
