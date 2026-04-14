<?php

namespace App\Policies;

class TaskPolicy extends TierOnePolicy
{
    protected string $viewPermission = 'view_tasks';
    protected string $addPermission = 'add_tasks';
    protected string $editPermission = 'edit_tasks';
    protected string $deletePermission = 'delete_tasks';
}
