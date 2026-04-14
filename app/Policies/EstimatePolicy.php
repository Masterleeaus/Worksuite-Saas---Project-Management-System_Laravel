<?php

namespace App\Policies;

class EstimatePolicy extends TierOnePolicy
{
    protected string $viewPermission = 'view_estimates';
    protected string $addPermission = 'add_estimates';
    protected string $editPermission = 'edit_estimates';
    protected string $deletePermission = 'delete_estimates';
}
