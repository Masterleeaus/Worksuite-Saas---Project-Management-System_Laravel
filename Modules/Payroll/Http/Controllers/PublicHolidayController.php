<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\Payroll\Entities\PayrollSetting;
use Modules\Payroll\Entities\PublicHoliday;
use Modules\Payroll\Services\VariableRateEngine;

class PublicHolidayController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('payroll::app.menu.publicHolidays');
        $this->activeSettingMenu = 'payroll_settings';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array(PayrollSetting::MODULE_NAME, $this->user->modules));
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        abort_403(user()->permission('manage_payroll_settings') == 'none');

        $year = $request->get('year', now()->year);
        $state = $request->get('state');

        $this->holidays = PublicHoliday::where('company_id', company()->id)
            ->whereYear('holiday_date', $year)
            ->when($state, fn($q) => $q->where(fn($q2) => $q2->where('state', $state)->orWhere('is_national', true)))
            ->orderBy('holiday_date')
            ->get();

        $this->year = $year;
        $this->selectedState = $state;
        $this->states = VariableRateEngine::AU_STATES;

        return view('payroll::public-holiday.index', $this->data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date',
            'name'         => 'required|string|max:255',
            'state'        => 'nullable|in:' . implode(',', VariableRateEngine::AU_STATES),
        ]);

        PublicHoliday::create([
            'company_id'   => company()->id,
            'holiday_date' => $request->holiday_date,
            'name'         => $request->name,
            'state'        => $request->state ?: null,
            'is_national'  => is_null($request->state),
            'is_manual'    => true,
        ]);

        return Reply::success(__('messages.recordSaved'));
    }

    public function destroy($id)
    {
        $holiday = PublicHoliday::where('company_id', company()->id)->findOrFail($id);
        $holiday->delete();
        return Reply::success(__('messages.deleteSuccess'));
    }
}
