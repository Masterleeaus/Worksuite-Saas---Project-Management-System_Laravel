<?php

namespace Modules\TitanPay\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;

class TitanPaySettingsController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'TitanPay Settings';
    }

    /**
     * Display the settings page.
     * GET /account/titanpay/settings
     */
    public function index()
    {
        $settings = $this->currentSettings();

        return view('titanpay::settings.index', compact('settings'));
    }

    /**
     * Persist settings to the .env / company_settings table.
     * POST /account/titanpay/settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'cryptomus_payment_api_key' => ['nullable', 'string', 'max:255'],
            'cryptomus_merchant_uuid'   => ['nullable', 'string', 'max:255'],
            'cryptomus_webhook_secret'  => ['nullable', 'string', 'max:255'],
            'cryptomus_currency'        => ['nullable', 'string', 'max:10'],
            'cryptomus_active'          => ['nullable', 'boolean'],
            'link_expiry_days'          => ['nullable', 'integer', 'min:1', 'max:365'],
            'qr_expiry_days'            => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        // Persist to company_settings when the model is available
        $this->persistSettings($request);

        return Reply::success('TitanPay settings saved successfully.');
    }

    // -------------------------------------------------------------------------

    private function currentSettings(): array
    {
        return [
            'cryptomus_payment_api_key' => config('titanpay.cryptomus.payment_api_key'),
            'cryptomus_merchant_uuid'   => config('titanpay.cryptomus.merchant_uuid'),
            'cryptomus_webhook_secret'  => config('titanpay.cryptomus.webhook_secret'),
            'cryptomus_currency'        => config('titanpay.cryptomus.currency', 'USD'),
            'cryptomus_active'          => config('titanpay.cryptomus.is_active', false),
            'link_expiry_days'          => config('titanpay.payment_link.expiry_days', 30),
            'qr_expiry_days'            => config('titanpay.qr.expiry_days', 7),
        ];
    }

    private function persistSettings(Request $request): void
    {
        // Use CompanySettings when available (same pattern as other modules)
        $settingsModel = null;

        foreach (['App\Models\CompanySetting', 'App\Models\Setting'] as $class) {
            if (class_exists($class)) {
                $settingsModel = $class;
                break;
            }
        }

        if (!$settingsModel) {
            return;
        }

        $map = [
            'cryptomus_payment_api_key' => 'titanpay_cryptomus_api_key',
            'cryptomus_merchant_uuid'   => 'titanpay_cryptomus_merchant_uuid',
            'cryptomus_webhook_secret'  => 'titanpay_cryptomus_webhook_secret',
            'cryptomus_currency'        => 'titanpay_cryptomus_currency',
            'cryptomus_active'          => 'titanpay_cryptomus_active',
            'link_expiry_days'          => 'titanpay_link_expiry_days',
            'qr_expiry_days'            => 'titanpay_qr_expiry_days',
        ];

        $companyId = user()->company_id ?? null;

        foreach ($map as $requestKey => $settingKey) {
            if ($request->has($requestKey)) {
                $settingsModel::updateOrCreate(
                    array_filter(
                        ['company_id' => $companyId, 'setting_name' => $settingKey],
                        fn($v) => $v !== null
                    ),
                    ['setting_value' => $request->input($requestKey, '')]
                );
            }
        }
    }
}
