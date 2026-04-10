<?php

namespace Modules\CustomerConnect\Policies;

use App\Models\User;
use Modules\CustomerConnect\Entities\Campaign;

class CampaignPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Campaign $campaign): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Campaign $campaign): bool { return true; }
    public function delete(User $user, Campaign $campaign): bool { return true; }
}
