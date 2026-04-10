<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Acl\Module;
use App\Models\Acl\ModuleCategory;
use App\Models\Acl\Role;
use App\Services\Admin\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): View
    {
        $stats         = $this->dashboardService->getPlatformStats();
        $signupTrend   = $this->dashboardService->getSignupTrend(30);
        $mrrTrend      = $this->dashboardService->getMRRTrend(30);
        $recentSignups = $this->dashboardService->getRecentSignups(5);
        $roles         = Role::count();
        $admins        = Admin::count();
        $modules       = Module::count();
        $categories    = ModuleCategory::count();


        return view('admin.dashboard', compact(
            'stats',
            'signupTrend',
            'mrrTrend',
            'recentSignups',
            'roles',
            'admins',
            'modules',
            'categories'
        ));
    }
}
