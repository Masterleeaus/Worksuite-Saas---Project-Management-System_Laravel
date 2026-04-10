<?php

namespace Modules\CustomerConnect\Services\Premium;

use Illuminate\Support\Facades\DB;

class EventLogger
{
    public function messageEvent(int $messageId, string $eventType, array $payload = []): void
    {
        if (!DB::getSchemaBuilder()->hasTable('customerconnect_message_events')) {
            return;
        }

        DB::table('customerconnect_message_events')->insert([
            'message_id' => $messageId,
            'event_type' => $eventType,
            'payload_json' => json_encode($payload),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function deliveryEvent(int $deliveryId, string $eventType, array $payload = []): void
    {
        if (!DB::getSchemaBuilder()->hasTable('customerconnect_delivery_events')) {
            return;
        }

        DB::table('customerconnect_delivery_events')->insert([
            'delivery_id' => $deliveryId,
            'event_type' => $eventType,
            'payload_json' => json_encode($payload),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
