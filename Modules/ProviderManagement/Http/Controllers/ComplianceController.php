<?php

namespace Modules\ProviderManagement\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use App\Models\EmployeeDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\ProviderManagement\Services\ComplianceExpiryService;

class ComplianceController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Provider Compliance';
    }

    /**
     * Show compliance details for an employee.
     */
    public function show(int $employeeId)
    {
        abort_403(user()->permission('manage_provider_compliance') !== 'all'
            && user()->id !== $employeeId);

        $this->employee = User::findOrFail($employeeId);
        $this->employeeDetail = EmployeeDetails::where('user_id', $employeeId)->firstOrFail();

        return view('providermanagement::employee.compliance_tab', $this->data);
    }

    /**
     * Update compliance fields for an employee.
     */
    public function update(Request $request, int $employeeId)
    {
        abort_403(user()->permission('manage_provider_compliance') !== 'all');

        $validated = $request->validate([
            'police_check_date'   => 'nullable|date',
            'police_check_expiry' => ['nullable', 'date', $request->filled('police_check_date') ? 'after_or_equal:police_check_date' : ''],
            'insurance_expiry'    => 'nullable|date',
            'wwcc_expiry'         => 'nullable|date',
            'abn'                 => 'nullable|string|max:20',
            'max_jobs_per_day'    => 'nullable|integer|min:1|max:20',
            'is_subcontractor'    => 'nullable|boolean',
        ]);

        EmployeeDetails::where('user_id', $employeeId)
            ->update($validated);

        return response()->json(['status' => 'success', 'message' => 'Compliance data updated.']);
    }

    /**
     * Dashboard listing all expiring compliance documents.
     */
    public function expiryDashboard(ComplianceExpiryService $service)
    {
        abort_403(user()->permission('manage_provider_compliance') !== 'all');

        $this->expiring = $service->getExpiringSoon(user()->company_id);
        $this->expired  = $service->getExpired(user()->company_id);

        return view('providermanagement::compliance.expiry_dashboard', $this->data);
    }
}
