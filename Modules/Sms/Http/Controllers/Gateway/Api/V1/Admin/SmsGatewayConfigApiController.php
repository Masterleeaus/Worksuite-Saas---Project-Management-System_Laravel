<?php

namespace Modules\Sms\Http\Controllers\Gateway\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

/**
 * SmsGatewayConfigApiController
 *
 * Merged from SMSModule API V1 Admin controller.
 * Exposes gateway configuration endpoints for the Sms module.
 */
class SmsGatewayConfigApiController extends Controller
{
    private BusinessSettings $businessSetting;

    public function __construct(BusinessSettings $businessSetting)
    {
        $this->businessSetting = $businessSetting;
    }

    /**
     * GET current SMS gateway config.
     */
    public function smsConfigGet(): JsonResponse
    {
        $dataValues = $this->businessSetting
            ->whereIn('settings_type', ['sms_config'])
            ->get();

        return response()->json(response_formatter(DEFAULT_200, $dataValues), 200);
    }

    /**
     * PUT save/update a gateway config.
     */
    public function smsConfigSet(Request $request): JsonResponse
    {
        $baseRules = [
            'gateway' => 'required|in:releans,twilio,nexmo,2factor,msg91,alphanet_sms',
            'mode'    => 'required|in:live,test',
        ];

        $gatewayRules = $this->gatewayValidationRules($request->input('gateway', ''));
        $validator = Validator::make($request->all(), array_merge($baseRules, $gatewayRules));

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->businessSetting->updateOrCreate(
            ['key_name' => $request['gateway'], 'settings_type' => 'sms_config'],
            [
                'key_name'      => $request['gateway'],
                'live_values'   => $validator->validated(),
                'test_values'   => $validator->validated(),
                'settings_type' => 'sms_config',
                'mode'          => $request['mode'],
                'is_active'     => $request['status'],
            ]
        );

        if ($request['status'] == 1) {
            $this->deactivateOtherGateways($request['gateway']);
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function gatewayValidationRules(string $gateway): array
    {
        return match ($gateway) {
            'releans'     => [
                'status'       => 'required|in:1,0',
                'api_key'      => 'required',
                'from'         => 'required',
                'otp_template' => 'required',
            ],
            'twilio'      => [
                'status'                => 'required|in:1,0',
                'sid'                   => 'required',
                'messaging_service_sid' => 'required',
                'token'                 => 'required',
                'from'                  => 'required',
                'otp_template'          => 'required',
            ],
            'nexmo'       => [
                'status'       => 'required|in:1,0',
                'api_key'      => 'required',
                'api_secret'   => 'required',
                'token'        => 'required',
                'from'         => 'required',
                'otp_template' => 'required',
            ],
            '2factor'     => [
                'status'  => 'required|in:1,0',
                'api_key' => 'required',
            ],
            'msg91'       => [
                'status'      => 'required|in:1,0',
                'template_id' => 'required',
                'auth_key'    => 'required',
            ],
            'alphanet_sms'=> [
                'status'       => 'required|in:1,0',
                'api_key'      => 'required',
                'otp_template' => 'required',
            ],
            default       => ['status' => 'required|in:1,0'],
        };
    }

    private function deactivateOtherGateways(string $activeGateway): void
    {
        foreach (['releans', 'twilio', 'nexmo', '2factor', 'msg91', 'alphanet_sms'] as $gateway) {
            if ($gateway === $activeGateway) {
                continue;
            }
            $keep = $this->businessSetting->where(['key_name' => $gateway, 'settings_type' => 'sms_config'])->first();
            if ($keep) {
                $hold = $keep->live_values;
                $hold['status'] = 0;
                $this->businessSetting->where(['key_name' => $gateway, 'settings_type' => 'sms_config'])->update([
                    'live_values' => $hold,
                    'test_values' => $hold,
                    'is_active'   => 0,
                ]);
            }
        }
    }
}
