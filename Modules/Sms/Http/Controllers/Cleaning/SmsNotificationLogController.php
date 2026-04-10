<?php

namespace Modules\Sms\Http\Controllers\Cleaning;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\Sms\Entities\SmsNotificationLog;

/**
 * Admin controller for viewing the SMS/WhatsApp notification delivery log.
 */
class SmsNotificationLogController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'SMS Notification Log';
        $this->activeSettingMenu = 'sms_setting';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('admin', user_roles()));
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $companyId = company()->id;

        $query = SmsNotificationLog::where('company_id', $companyId)
            ->with('user')
            ->orderByDesc('created_at');

        if ($request->filled('channel')) {
            $query->where('channel', $request->input('channel'));
        }

        if ($request->filled('trigger')) {
            $query->where('trigger_type', $request->input('trigger'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $logs = $query->paginate(50)->withQueryString();

        return view('sms::cleaning.notification-log', compact('logs'));
    }
}
