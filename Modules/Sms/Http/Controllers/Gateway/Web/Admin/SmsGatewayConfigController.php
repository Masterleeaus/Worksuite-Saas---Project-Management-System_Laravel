<?php

namespace Modules\Sms\Http\Controllers\Gateway\Web\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Modules\PaymentModule\Entities\Setting;
use Illuminate\Contracts\Foundation\Application;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

/**
 * SmsGatewayConfigController
 *
 * Merged from SMSModule Web Admin controller.
 * Handles gateway config UI for the Sms module (Worksuite/WorksuiteSaaS hybrid).
 */
class SmsGatewayConfigController extends Controller
{
    private Setting $addonSettings;
    private BusinessSettings $businessSetting;

    public function __construct(Setting $addonSettings, BusinessSettings $businessSetting)
    {
        $this->addonSettings = $addonSettings;
        $this->businessSetting = $businessSetting;
    }

    /**
     * Show the SMS gateway config page.
     */
    public function smsConfigGet(): View|Factory|Application
    {
        $publishedStatus = 0;
        $paymentPublishedStatus = config('get_payment_publish_status');
        if (isset($paymentPublishedStatus[0]['is_published'])) {
            $publishedStatus = $paymentPublishedStatus[0]['is_published'];
        }

        $routes = config('addon_admin_routes', []);
        $desiredName = 'sms_setup';
        $paymentUrl = '';

        foreach ($routes as $routeArray) {
            foreach ($routeArray as $route) {
                if ($route['name'] === $desiredName) {
                    $paymentUrl = $route['url'];
                    break 2;
                }
            }
        }

        $dataValues = $this->addonSettings
            ->whereIn('settings_type', ['sms_config'])
            ->whereIn('key_name', array_column(SMS_GATEWAY, 'key'))
            ->get();

        return view('sms::admin.sms-config', compact('dataValues', 'publishedStatus', 'paymentUrl'));
    }

    /**
     * Save/update a gateway's config.
     */
    public function smsConfigSet(Request $request): RedirectResponse|JsonResponse
    {
        $validation = [
            'gateway' => 'required|in:releans,twilio,nexmo,2factor,msg91,alphanet_sms',
            'mode'    => 'required|in:live,test',
        ];

        $additionalData = $this->gatewayValidationRules($request->input('gateway', ''));
        $validated = $request->validate(array_merge($validation, $additionalData));

        $this->addonSettings->updateOrCreate(
            ['key_name' => $request['gateway'], 'settings_type' => 'sms_config'],
            [
                'key_name'      => $request['gateway'],
                'live_values'   => $validated,
                'test_values'   => $validated,
                'settings_type' => 'sms_config',
                'mode'          => $request['mode'],
                'is_active'     => $request['status'],
            ]
        );

        if ($request['status'] == 1) {
            $this->deactivateOtherGateways($request['gateway']);
            $this->disableFirebaseOtpVerification();
        }

        if ($request->ajax()) {
            return response()->json(response_formatter(constant: DEFAULT_UPDATE_200), 200);
        }

        Toastr::success(translate(DEFAULT_UPDATE_200['message']));
        return back();
    }

    /**
     * Toggle a single gateway's active status.
     */
    public function updateGatewayStatus(string $gateway, int $status): JsonResponse
    {
        $record = $this->addonSettings->where(['key_name' => $gateway, 'settings_type' => 'sms_config'])->first();

        if (! $record) {
            return response()->json(['error' => translate('Gateway not found.')], 404);
        }

        $liveValues = $record->live_values;
        $liveValues['status'] = (int) $status;

        if ($status == 1 && (in_array(null, $liveValues, true) || in_array('', $liveValues, true))) {
            return response()->json([
                'response_code' => 'default_fail_200',
                'error'         => translate('Cannot update status. Please complete all required fields first.'),
            ], 200);
        }

        $this->addonSettings->where(['key_name' => $gateway, 'settings_type' => 'sms_config'])->update([
            'key_name'      => $gateway,
            'live_values'   => $liveValues,
            'test_values'   => $liveValues,
            'settings_type' => $record->settings_type,
            'mode'          => $record->mode,
            'is_active'     => $status,
        ]);

        if ($status == 1) {
            $this->deactivateOtherGateways($gateway);
            $this->disableFirebaseOtpVerification();
        }

        return response()->json(response_formatter(constant: DEFAULT_UPDATE_200), 200);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function gatewayValidationRules(string $gateway): array
    {
        return match ($gateway) {
            'releans'     => [
                'status'       => 'required|in:1,0',
                'api_key'      => 'required_if:status,1',
                'from'         => 'required_if:status,1',
                'otp_template' => 'required_if:status,1',
            ],
            'twilio'      => [
                'status'               => 'required|in:1,0',
                'sid'                  => 'required_if:status,1',
                'messaging_service_sid'=> 'required_if:status,1',
                'token'                => 'required_if:status,1',
                'from'                 => 'required_if:status,1',
                'otp_template'         => 'required_if:status,1',
            ],
            'nexmo'       => [
                'status'       => 'required|in:1,0',
                'api_key'      => 'required_if:status,1',
                'api_secret'   => 'required_if:status,1',
                'token'        => 'required_if:status,1',
                'from'         => 'required_if:status,1',
                'otp_template' => 'required_if:status,1',
            ],
            '2factor'     => [
                'status'       => 'required|in:1,0',
                'api_key'      => 'required_if:status,1',
                'otp_template' => 'required_if:status,1',
            ],
            'msg91'       => [
                'status'      => 'required|in:1,0',
                'template_id' => 'required_if:status,1',
                'auth_key'    => 'required_if:status,1',
            ],
            'alphanet_sms'=> [
                'status'       => 'required|in:1,0',
                'api_key'      => 'required_if:status,1',
                'otp_template' => 'required_if:status,1',
            ],
            default       => ['status' => 'required|in:1,0'],
        };
    }

    private function deactivateOtherGateways(string $activeGateway): void
    {
        foreach (SMS_GATEWAY as $data) {
            if ($activeGateway === $data['key']) {
                continue;
            }
            $keep = $this->addonSettings->where(['key_name' => $data['key'], 'settings_type' => 'sms_config'])->first();
            if ($keep) {
                $hold = $keep->live_values;
                $hold['status'] = 0;
                $this->addonSettings->where(['key_name' => $data['key'], 'settings_type' => 'sms_config'])->update([
                    'live_values' => $hold,
                    'test_values' => $hold,
                    'is_active'   => 0,
                ]);
            }
        }
    }

    private function disableFirebaseOtpVerification(): void
    {
        $setting = $this->businessSetting
            ->where(['key_name' => 'firebase_otp_verification', 'settings_type' => 'third_party'])
            ->first();

        if ($setting) {
            $liveValues = $setting->live_values;
            $liveValues['status'] = '0';
            $this->businessSetting->where('id', $setting->id)->update([
                'live_values' => json_encode($liveValues),
                'is_active'   => 0,
            ]);
        }
    }
}
