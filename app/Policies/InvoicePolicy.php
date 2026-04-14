<?php

namespace App\Policies;

class InvoicePolicy extends TierOnePolicy
{
    protected string $viewPermission = 'view_invoices';
    protected string $addPermission = 'add_invoices';
    protected string $editPermission = 'edit_invoices';
    protected string $deletePermission = 'delete_invoices';
}
