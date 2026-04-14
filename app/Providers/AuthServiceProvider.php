<?php

namespace App\Providers;

use App\Models\Contract;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Task;
use App\Policies\ContractPolicy;
use App\Policies\EstimatePolicy;
use App\Policies\InvoicePolicy;
use App\Policies\LeadPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Lead::class => LeadPolicy::class,
        Estimate::class => EstimatePolicy::class,
        Task::class => TaskPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        Contract::class => ContractPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }

}
