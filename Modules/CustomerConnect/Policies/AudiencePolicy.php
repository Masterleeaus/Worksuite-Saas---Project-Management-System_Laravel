<?php

namespace Modules\CustomerConnect\Policies;

use App\Models\User;
use Modules\CustomerConnect\Entities\Audience;

class AudiencePolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Audience $audience): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Audience $audience): bool { return true; }
    public function delete(User $user, Audience $audience): bool { return true; }
}
