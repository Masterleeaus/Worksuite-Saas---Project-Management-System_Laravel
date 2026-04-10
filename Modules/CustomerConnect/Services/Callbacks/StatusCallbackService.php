<?php

namespace Modules\CustomerConnect\Services\Callbacks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Modules\CustomerConnect\Services\Premium\EventLogger;

/**
 * Provider delivery receipts/status callbacks.
 *
 * This service updates:
 * - customerconnect_messages.status (if provider_message_id matches)
 * - customerconnect_deliveries.status (if provider_message_id matches)
 * and writes an event row when tables exist.
 *
 * It is intentionally defensive: if columns/tables are missing, it no-ops safely.
 */
class StatusCallbackService
{
    public function __construct(protected EventLogger $logger)
    {
    }

    public function handleTwilioStatusCallback(Request $request)
    {
        // Twilio typically sends: MessageSid, MessageStatus, To, From, ErrorCode, ErrorMessage
        $messageSid = $request->input('MessageSid') ?: $request->input('SmsSid');
        $status     = $request->input('MessageStatus') ?: $request->input('SmsStatus');

        if (!$messageSid || !$status) {
            return response()->json(['ok' => true, 'ignored' => true]);
        }

        $mapped = $this->mapTwilioStatus($status);

        $this->updateByProviderMessageId('twilio', $messageSid, $mapped, [
            'raw_status' => $status,
            'to' => $request->input('To'),
            'from' => $request->input('From'),
            'error_code' => $request->input('ErrorCode'),
            'error_message' => $request->input('ErrorMessage'),
        ]);

        return response()->json(['ok' => true]);
    }

    public function handleVonageStatusCallback(Request $request)
    {
        // Vonage (Nexmo) delivery receipt fields vary:
        // messageId, status, to, err-code, network-code, price, scts
        $messageId = $request->input('messageId') ?: $request->input('message-id');
        $status    = $request->input('status');

        if (!$messageId || !$status) {
            return response()->json(['ok' => true, 'ignored' => true]);
        }

        $mapped = $this->mapVonageStatus($status);

        $this->updateByProviderMessageId('vonage', $messageId, $mapped, [
            'raw_status' => $status,
            'to' => $request->input('to'),
            'err_code' => $request->input('err-code'),
            'network_code' => $request->input('network-code'),
            'price' => $request->input('price'),
            'scts' => $request->input('scts'),
        ]);

        return response()->json(['ok' => true]);
    }

    protected function updateByProviderMessageId(string $provider, string $providerMessageId, string $status, array $meta = [])
    {
        // Update messages
        if (Schema::hasTable('customerconnect_messages') && Schema::hasColumn('customerconnect_messages', 'provider_message_id')) {
            $msg = DB::table('customerconnect_messages')->where('provider_message_id', $providerMessageId)->first();
            if ($msg) {
                DB::table('customerconnect_messages')->where('id', $msg->id)->update([
                    'status' => $status,
                    'updated_at' => now(),
                ]);
                $this->logger->messageEvent((int)$msg->id, 'provider_status', array_merge($meta, [
                    'provider' => $provider,
                    'provider_message_id' => $providerMessageId,
                    'mapped_status' => $status,
                ]));
            }
        }

        // Update deliveries
        if (Schema::hasTable('customerconnect_deliveries') && Schema::hasColumn('customerconnect_deliveries', 'provider_message_id')) {
            $del = DB::table('customerconnect_deliveries')->where('provider_message_id', $providerMessageId)->first();
            if ($del) {
                // Only promote state forward (avoid reverting sent -> queued)
                $next = $this->promoteStatus($del->status ?? null, $status);
                DB::table('customerconnect_deliveries')->where('id', $del->id)->update([
                    'status' => $next,
                    'updated_at' => now(),
                ]);
                $this->logger->deliveryEvent((int)$del->id, 'provider_status', array_merge($meta, [
                    'provider' => $provider,
                    'provider_message_id' => $providerMessageId,
                    'mapped_status' => $next,
                ]));
            }
        }
    }

    protected function promoteStatus(?string $current, string $incoming): string
    {
        $order = ['queued'=>0,'sending'=>1,'sent'=>2,'delivered'=>3,'read'=>4,'failed'=>99,'skipped'=>98];
        $c = $order[$current] ?? 0;
        $i = $order[$incoming] ?? 0;
        if ($current === 'failed' || $current === 'skipped') {
            return $current;
        }
        return ($i >= $c) ? $incoming : ($current ?? $incoming);
    }

    protected function mapTwilioStatus(string $status): string
    {
        $s = strtolower($status);
        return match ($s) {
            'queued', 'accepted' => 'queued',
            'sending' => 'sending',
            'sent' => 'sent',
            'delivered' => 'delivered',
            'read' => 'read',
            'undelivered', 'failed' => 'failed',
            default => 'sent',
        };
    }

    protected function mapVonageStatus(string $status): string
    {
        $s = strtolower($status);
        return match ($s) {
            'accepted', 'buffered' => 'queued',
            'delivered' => 'delivered',
            'failed', 'rejected', 'expired' => 'failed',
            default => 'sent',
        };
    }
}
