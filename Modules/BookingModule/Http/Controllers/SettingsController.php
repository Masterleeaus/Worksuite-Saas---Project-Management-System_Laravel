<?php

namespace Modules\BookingModule\Http\Controllers;

use Modules\BookingModule\Services\NotificationPreferencesService;
use Modules\BookingModule\Http\Requests\UpdateNotificationPreferencesRequest;

use Illuminate\Routing\Controller;
use Modules\BookingModule\Http\Requests\UpdateAutoAssignSettingsRequest;
use Modules\BookingModule\Http\Requests\UpdatePublicSpamSettingsRequest;
use Modules\BookingModule\Services\AppointmentSettingsService;

class SettingsController extends Controller
{
    public function __construct(protected AppointmentSettingsService $settings)
    {
        $this->middleware(['auth']);
    }

    public function autoAssign()
    {
        $enabled = (bool)$this->settings->get('auto_assign.enabled', config('bookingmodule::auto_assign.enabled', false));
        $strategy = (string)$this->settings->get('auto_assign.strategy', config('bookingmodule::auto_assign.strategy', 'least_busy'));
        $requirePermission = (bool)$this->settings->get('auto_assign.require_permission', config('bookingmodule::auto_assign.require_permission', true));
        $eligiblePermission = (string)$this->settings->get('auto_assign.eligible_permission', config('bookingmodule::auto_assign.eligible_permission', 'appointment.assign'));

        return view('bookingmodule::settings.auto_assign', compact('enabled', 'strategy', 'requirePermission', 'eligiblePermission'));
    }

    public function updateAutoAssign(UpdateAutoAssignSettingsRequest $request)
    {
        $this->settings->set('auto_assign.enabled', (bool)$request->boolean('enabled'));
        $this->settings->set('auto_assign.strategy', $request->input('strategy'));
        $this->settings->set('auto_assign.require_permission', (bool)$request->boolean('require_permission'));
        $this->settings->set('auto_assign.eligible_permission', $request->input('eligible_permission', 'appointment.assign'));

        return redirect()->back()->with('success', __('bookingmodule::settings.saved'));
    }


    public function legacyImport()
    {
        // UI only. Import is performed via artisan command intentionally.
        return view('bookingmodule::settings.legacy_import');
    }


    public function publicSpam()
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(auth()->user(), 'appointment settings manage')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $enable_honeypot = (bool) $this->settings->get('public.enable_honeypot', true);
        $honeypot_min_seconds = (int) $this->settings->get('public.honeypot_min_seconds', (int)config('bookingmodule.public.honeypot_min_seconds', 3));
        $rate_limit_per_minute = (int) $this->settings->get('public.rate_limit_per_minute', (int)config('bookingmodule.public.rate_limit_per_minute', 60));

        return view('bookingmodule::settings.public_spam', compact('enable_honeypot', 'honeypot_min_seconds', 'rate_limit_per_minute'));
    }

    public function updatePublicSpam(UpdatePublicSpamSettingsRequest $request)
    {
        $this->settings->set('public.enable_honeypot', (bool)$request->boolean('enable_honeypot'));
        $this->settings->set('public.honeypot_min_seconds', (int)$request->input('honeypot_min_seconds'));
        $this->settings->set('public.rate_limit_per_minute', (int)$request->input('rate_limit_per_minute'));

        return redirect()->back()->with('success', __('Settings updated.'));
    }
public function notificationPreferences(NotificationPreferencesService $prefs)
{
    $companyId = function_exists('company') && company() ? company()->id : null;
    $pref = $prefs->getForUser(auth()->id(), $companyId);

    return view('bookingmodule::settings.notification_preferences', compact('pref'));
}

public function updateNotificationPreferences(UpdateNotificationPreferencesRequest $request, NotificationPreferencesService $prefs)
{
    $companyId = function_exists('company') && company() ? company()->id : null;

    $data = $request->validated();
    // unchecked checkboxes won't be present
    foreach ([
        'channel_email','channel_database','notify_assigned','notify_reassigned','notify_unassigned',
        'notify_rescheduled','notify_cancelled','daily_digest'
    ] as $k) {
        $data[$k] = (bool)($request->input($k, false));
    }

    $prefs->saveForUser(auth()->id(), $companyId, $data);

    return redirect()->back()->with('success', __('bookingmodule::settings.updated'));
}



public function staffCapacity(\Modules\BookingModule\Services\ScheduleCapacityService $capacity)
{
    $companyId = function_exists('company') && company() ? company()->id : null;
    $capacities = method_exists($capacity, 'allForCompany') ? $capacity->allForCompany($companyId) : [];
    return view('bookingmodule::settings.staff_capacity', compact('capacities'));
}

public function updateStaffCapacity(\Illuminate\Http\Request $request, \Modules\BookingModule\Services\ScheduleCapacityService $capacity)
{
    $companyId = function_exists('company') && company() ? company()->id : null;
    $rows = $request->input('capacity', []);
    foreach ($rows as $userId => $value) { if (method_exists($capacity, 'setCapacity')) { $capacity->setCapacity($companyId, (int)$userId, (int)$value); } }
    return redirect()->back()->with('success', __('Settings updated.'));
}

}
