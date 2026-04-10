<?php

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Modules\BookingModule\Http\Traits\BookingTrait;
use Modules\CartModule\Entities\Cart;
use Modules\CartModule\Entities\CartServiceInfo;
use Modules\PaymentModule\Lib\PaymentResponse;
use Modules\PaymentModule\Traits\PaymentHelperTrait;
use Modules\UserManagement\Entities\User;



if (!function_exists('titan_ai_payment_signal')) {
    /**
     * Emit a payment-related signal to TitanZero (safe; no hard dependency).
     */
    function titan_ai_payment_signal(string $type, $data = null, ?int $tenantId = null): void
    {
        // Non-marketplace mode: block booking payment capture hooks
        if (!config('features.marketplace_enabled', false)) {
            $lower = strtolower($type);
            if (str_contains($lower, 'booking') || str_contains($lower, 'wallet')) {
                return;
            }
        }

        try {
            // Resolve bridge if available
            if (class_exists(\Modules\PaymentModule\Services\Ai\TitanZeroBridge::class)) {
                $bridge = app(\Modules\PaymentModule\Services\Ai\TitanZeroBridge::class);

                // data_get works for arrays/objects
                $payload = [
                    'type' => $type,
                    'transaction_id' => data_get($data, 'transaction_id'),
                    'payer_id' => data_get($data, 'payer_id'),
                    'amount' => data_get($data, 'amount'),
                    'payment_method' => data_get($data, 'payment_method', data_get($data, 'additional_data.payment_method')),
                    'payment_request_id' => data_get($data, 'id'),
                    'status' => data_get($data, 'status'),
                    'additional_data' => data_get($data, 'additional_data'),
                ];

                $bridge->ingestSignal([
                    'type' => $type,
                    'payload' => $payload,
                ], $tenantId);

// Ask TitanZero for proposals (safe baseline: log-only) on notable payment events.
if (class_exists(\Modules\PaymentModule\Services\Ai\ProposalRunner::class)) {
    app(\Modules\PaymentModule\Services\Ai\ProposalRunner::class)->proposeAndLog([
        'module' => 'PaymentModule',
        'event'  => $type,
        'entity_type' => 'payment',
        'entity_id' => data_get($data, 'transaction_id') ?? data_get($data, 'id'),
        'facts'  => [
            'amount' => data_get($data, 'amount'),
            'currency' => data_get($data, 'currency'),
            'payment_method' => data_get($data, 'payment_method'),
            'status' => data_get($data, 'status') ?? $type,
        ],
    ], $tenantId);
}
            }
        } catch (\Throwable $e) {
            // Never break payment flow; log if logger available
            if (function_exists('logger')) {
                logger()->warning('TitanZero payment signal failed: '.$e->getMessage(), ['type' => $type]);
            }
        }
    }
}

if (!function_exists('digital_payment_success')) {
    /**
     * @param $data
     * @return void
     */
    function digital_payment_success($data): void
    {
        titan_ai_payment_signal('payment_success', $data);
        PaymentResponse::success($data);
    }
}

if (!function_exists('digital_payment_fail')) {
    /**
     * @param $data
     * @return void
     */
    function digital_payment_fail($data): void
    {
        titan_ai_payment_signal('payment_fail', $data);
        //
    }
}
if (!function_exists('repeat_booking_payment_success')) {
    /**
     * @param $data
     * @return void
     */
    function repeat_booking_payment_success($data): void
    {
        titan_ai_payment_signal('repeat_booking_payment_success', $data);
        PaymentResponse::repeatBookingPaymentSuccess($data);
    }
}

if (!function_exists('switch_offline_to_digital_payment_success')) {
    /**
     * @param $data
     * @return void
     */
    function switch_offline_to_digital_payment_success($data): void
    {
        titan_ai_payment_signal('offline_to_digital_payment_success', $data);
        PaymentResponse::switchOfflineToDigitalPaymentSuccess($data);
    }
}

if (!function_exists('switch_offline_to_digital_payment_fail')) {
    /**
     * @param $data
     * @return void
     */
    function switch_offline_to_digital_payment_fail($data): void
    {
        titan_ai_payment_signal('offline_to_digital_payment_fail', $data);
        //
    }
}

if (!function_exists('subscription_success')) {
    /**
     * @param $data
     * @return void
     */
    function subscription_success($data): void
    {
        titan_ai_payment_signal('subscription_success', $data);
        $additional_data = json_decode($data['additional_data'], true);
        $packageStatus = collect([
            'package_status' => $additional_data['package_status'] ?? null,
        ]);

        if ($packageStatus['package_status'] == 'subscription_purchase'){
            PaymentResponse::purchaseSubscriptionSuccess($data);
        }elseif ($packageStatus['package_status'] == 'subscription_renew'){
            PaymentResponse::renewSubscriptionSuccess($data);
        }elseif ($packageStatus['package_status'] == 'subscription_shift'){
            PaymentResponse::shiftSubscriptionSuccess($data);
        }elseif ($packageStatus['package_status'] == 'business_plan_change'){
            PaymentResponse::businessPlanChangeSuccess($data);
        }
    }
}

if (!function_exists('subscription_fail')) {
    /**
     * @param $data
     * @return void
     */
    function subscription_fail($data): void
    {
        titan_ai_payment_signal('subscription_fail', $data);
        $additional_data = json_decode($data['additional_data'], true);
        $packageStatus = collect([
            'package_status' => $additional_data['package_status'] ?? null,
        ]);

        if ($packageStatus['package_status'] == 'subscription_purchase') {
            PaymentResponse::purchaseSubscriptionFailed($data);
        }
    }
}

