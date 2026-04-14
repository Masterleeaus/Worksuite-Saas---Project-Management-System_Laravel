<?php

namespace App\Policies;

class PaymentPolicy extends TierOnePolicy
{
    protected string $viewPermission = 'view_payments';
    protected string $addPermission = 'add_payments';
    protected string $editPermission = 'edit_payments';
    protected string $deletePermission = 'delete_payments';
}
