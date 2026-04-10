<?php

return [
    'name' => 'FSMSales',

    /*
    |--------------------------------------------------------------------------
    | Billing Policies
    |--------------------------------------------------------------------------
    | manual        – admin creates invoices manually
    | on_completion – invoice draft created when order stage is marked complete
    | on_timesheet  – invoice based on logged timesheet hours × hourly_rate
    */
    'billing_policies' => [
        'manual'        => 'Manual',
        'on_completion' => 'On Completion',
        'on_timesheet'  => 'On Timesheet',
    ],

    /*
    |--------------------------------------------------------------------------
    | Invoice Statuses
    |--------------------------------------------------------------------------
    */
    'invoice_statuses' => [
        'draft'   => 'Draft',
        'sent'    => 'Sent',
        'paid'    => 'Paid',
        'overdue' => 'Overdue',
        'void'    => 'Void',
    ],

    /*
    |--------------------------------------------------------------------------
    | Recurring Billing Schedules
    |--------------------------------------------------------------------------
    */
    'billing_schedules' => [
        'per_visit'  => 'Per Visit',
        'monthly'    => 'Monthly',
        'quarterly'  => 'Quarterly',
        'annual'     => 'Annual Prepay',
    ],

    /*
    |--------------------------------------------------------------------------
    | Invoice Number Prefix
    |--------------------------------------------------------------------------
    */
    'invoice_prefix' => env('FSM_INVOICE_PREFIX', 'INV'),

    /*
    |--------------------------------------------------------------------------
    | Default Payment Terms (days)
    |--------------------------------------------------------------------------
    */
    'payment_terms_days' => (int) env('FSM_PAYMENT_TERMS_DAYS', 14),
];
