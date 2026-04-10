<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Payroll\Entities\CleanerRateConfig;
use Modules\Payroll\Entities\PayrollSetting;

class CleanerRateConfigController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('payroll::app.menu.cleanerRateConfig');
        $this->activeSettingMenu = 'payroll_settings';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array(PayrollSetting::MODULE_NAME, $this->user->modules));
            return $next($request);
        });
    }

    public function index()
    {
        abort_403(user()->permission('manage_payroll_settings') == 'none');

        $this->configs = CleanerRateConfig::with('user')
            ->where('company_id', company()->id)
            ->orderBy('user_id')
            ->get();

        $this->employees = User::join('employee_details', 'employee_details.user_id', 'users.id')
            ->select('users.id', 'users.name')
            ->get();

        return view('payroll::cleaner-rate-config.index', $this->data);
    }

    public function create()
    {
        $this->employees = User::join('employee_details', 'employee_details.user_id', 'users.id')
            ->select('users.id', 'users.name')
            ->get();

        $html = view('payroll::cleaner-rate-config.ajax.create', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'html' => $html]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'base_rate'                    => 'required|numeric|min:0',
            'night_rate_multiplier'        => 'required|numeric|min:1',
            'night_rate_cutoff'            => 'required|date_format:H:i',
            'saturday_multiplier'          => 'required|numeric|min:1',
            'sunday_multiplier'            => 'required|numeric|min:1',
            'public_holiday_multiplier'    => 'required|numeric|min:1',
            'public_holiday_fixed_rate'    => 'nullable|numeric|min:0',
            'commission_per_room'          => 'nullable|numeric|min:0',
        ]);

        CleanerRateConfig::create([
            'company_id'                => company()->id,
            'user_id'                   => $request->user_id ?: null,
            'contract_ref'              => $request->contract_ref ?: null,
            'base_rate'                 => $request->base_rate,
            'night_rate_multiplier'     => $request->night_rate_multiplier,
            'night_rate_cutoff'         => $request->night_rate_cutoff . ':00',
            'saturday_multiplier'       => $request->saturday_multiplier,
            'sunday_multiplier'         => $request->sunday_multiplier,
            'public_holiday_multiplier' => $request->public_holiday_multiplier,
            'public_holiday_fixed_rate' => $request->public_holiday_fixed_rate ?: null,
            'commission_per_room'       => $request->commission_per_room ?? 0,
            'is_active'                 => true,
            'notes'                     => $request->notes,
        ]);

        return Reply::success(__('messages.recordSaved'));
    }

    public function edit($id)
    {
        $this->config = CleanerRateConfig::where('company_id', company()->id)->findOrFail($id);
        $this->employees = User::join('employee_details', 'employee_details.user_id', 'users.id')
            ->select('users.id', 'users.name')
            ->get();

        $html = view('payroll::cleaner-rate-config.ajax.edit', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'html' => $html]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'base_rate'                    => 'required|numeric|min:0',
            'night_rate_multiplier'        => 'required|numeric|min:1',
            'night_rate_cutoff'            => 'required|date_format:H:i',
            'saturday_multiplier'          => 'required|numeric|min:1',
            'sunday_multiplier'            => 'required|numeric|min:1',
            'public_holiday_multiplier'    => 'required|numeric|min:1',
            'public_holiday_fixed_rate'    => 'nullable|numeric|min:0',
            'commission_per_room'          => 'nullable|numeric|min:0',
        ]);

        $config = CleanerRateConfig::where('company_id', company()->id)->findOrFail($id);

        $config->update([
            'user_id'                   => $request->user_id ?: null,
            'contract_ref'              => $request->contract_ref ?: null,
            'base_rate'                 => $request->base_rate,
            'night_rate_multiplier'     => $request->night_rate_multiplier,
            'night_rate_cutoff'         => $request->night_rate_cutoff . ':00',
            'saturday_multiplier'       => $request->saturday_multiplier,
            'sunday_multiplier'         => $request->sunday_multiplier,
            'public_holiday_multiplier' => $request->public_holiday_multiplier,
            'public_holiday_fixed_rate' => $request->public_holiday_fixed_rate ?: null,
            'commission_per_room'       => $request->commission_per_room ?? 0,
            'is_active'                 => (bool) $request->is_active,
            'notes'                     => $request->notes,
        ]);

        return Reply::success(__('messages.recordUpdated'));
    }

    public function destroy($id)
    {
        CleanerRateConfig::where('company_id', company()->id)->findOrFail($id)->delete();
        return Reply::success(__('messages.deleteSuccess'));
    }
}
