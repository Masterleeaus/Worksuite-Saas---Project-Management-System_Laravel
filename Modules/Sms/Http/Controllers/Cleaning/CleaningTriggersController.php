<?php

namespace Modules\Sms\Http\Controllers\Cleaning;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\Sms\Entities\SmsNotificationSetting;
use Modules\Sms\Enums\SmsNotificationSlug;

/**
 * Admin controller for managing cleaning-specific SMS/WhatsApp trigger settings
 * and message template editing.
 */
class CleaningTriggersController extends AccountBaseController
{
    private const CLEANING_SLUGS = [
        SmsNotificationSlug::CleanerDispatched,
        SmsNotificationSlug::CleanerCheckedIn,
        SmsNotificationSlug::CleaningJobComplete,
        SmsNotificationSlug::CleaningUpcomingReminder,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Cleaning SMS Triggers';
        $this->activeSettingMenu = 'sms_setting';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('admin', user_roles()));
            return $next($request);
        });
    }

    public function index()
    {
        $companyId = company()->id;

        $settings = [];

        foreach (self::CLEANING_SLUGS as $slug) {
            $record = SmsNotificationSetting::firstOrNew([
                'company_id'   => $companyId,
                'slug'         => $slug->value,
                'setting_name' => $slug->label(),
            ]);

            $settings[$slug->value] = [
                'slug'            => $slug,
                'record'          => $record,
                'default_template' => __($slug->translationString()),
            ];
        }

        return view('sms::cleaning.triggers', compact('settings'));
    }

    public function update(Request $request)
    {
        $companyId = company()->id;

        foreach (self::CLEANING_SLUGS as $slug) {
            $record = SmsNotificationSetting::firstOrNew([
                'company_id'   => $companyId,
                'slug'         => $slug->value,
                'setting_name' => $slug->label(),
            ]);

            $record->send_sms      = $request->boolean('enabled.' . $slug->value) ? 'yes' : 'no';
            $record->custom_template = $request->input('template.' . $slug->value);
            $record->save();
        }

        return back()->with('success', 'Cleaning trigger settings saved.');
    }
}
