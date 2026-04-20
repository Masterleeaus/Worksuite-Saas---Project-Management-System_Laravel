<?php

namespace Modules\FSMSkill\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Modules\FSMSkill\Models\FSMEmployeeSkill;

class ExpiryDashboardController extends Controller
{
    public function index()
    {
        $days = (int) config('fsmskill.expiry_dashboard_days', 60);

        // Expired
        $expired = FSMEmployeeSkill::with(['user', 'skill.skillType', 'skillLevel'])
            ->where('company_id', $this->companyId())
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', Carbon::today())
            ->orderBy('expiry_date')
            ->get();

        // Expiring within configured window
        $expiringSoon = FSMEmployeeSkill::with(['user', 'skill.skillType', 'skillLevel'])
            ->where('company_id', $this->companyId())
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', Carbon::today())
            ->whereDate('expiry_date', '<=', Carbon::today()->addDays($days))
            ->orderBy('expiry_date')
            ->get();

        return view('fsmskill::expiry_dashboard.index', compact('expired', 'expiringSoon', 'days'));
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int) $user->company_id;
    }
}
