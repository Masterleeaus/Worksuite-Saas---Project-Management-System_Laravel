<?php

namespace App\Policies;

class ContractPolicy extends TierOnePolicy
{
    protected string $viewPermission = 'view_contract';
    protected string $addPermission = 'add_contract';
    protected string $editPermission = 'edit_contract';
    protected string $deletePermission = 'delete_contract';
}
