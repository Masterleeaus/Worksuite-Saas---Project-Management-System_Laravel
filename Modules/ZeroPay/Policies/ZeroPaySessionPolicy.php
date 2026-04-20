<?php

namespace Modules\ZeroPay\Policies;

use App\Models\User;
use Modules\ZeroPay\Models\ZeroPaySession;

class ZeroPaySessionPolicy
{
    public function view(User $user, ZeroPaySession $session): bool
    {
        return (int) $user->company_id === (int) $session->company_id;
    }

    public function update(User $user, ZeroPaySession $session): bool
    {
        return (int) $user->company_id === (int) $session->company_id;
    }
}
