<?php

namespace Modules\StaffCompliance\Http\Controllers\Web\Admin;

use Illuminate\Routing\Controller;
use Modules\StaffCompliance\Services\ComplianceDashboardService;

class ComplianceDashboardController extends Controller
{
    private ComplianceDashboardService $service;

    public function __construct(ComplianceDashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);
        abort_if(user()->permission('view_compliance') == 'none', 403);

        $statuses = $this->service->getComplianceStatus();
        $summary  = $this->service->getSummaryCounts();

        return view('staffcompliance::dashboard', compact('statuses', 'summary'));
    }
}
