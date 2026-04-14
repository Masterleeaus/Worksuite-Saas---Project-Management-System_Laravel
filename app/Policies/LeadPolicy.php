<?php

namespace App\Policies;

class LeadPolicy extends TierOnePolicy
{
    protected string $viewPermission = 'view_lead';
    protected string $addPermission = 'add_lead';
    protected string $editPermission = 'edit_lead';
    protected string $deletePermission = 'delete_lead';
}
