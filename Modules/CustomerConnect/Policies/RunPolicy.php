<?php

namespace Modules\CustomerConnect\Policies;

use App\Models\User;
use Modules\CustomerConnect\Entities\CampaignRun;

class RunPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, CampaignRun $run): bool { return true; }
}
