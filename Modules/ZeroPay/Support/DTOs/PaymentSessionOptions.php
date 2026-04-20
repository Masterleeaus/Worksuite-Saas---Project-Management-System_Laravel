<?php

namespace Modules\ZeroPay\Support\DTOs;

class PaymentSessionOptions
{
    public function __construct(
        public readonly int $invoiceId,
        public readonly ?float $amount = null,
        public readonly string $sourceType = 'invoice',
        public readonly ?string $sourceReference = null,
        public readonly ?int $bookingId = null,
        public readonly ?string $notes = null,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            invoiceId: (int) ($payload['invoice_id'] ?? 0),
            amount: isset($payload['amount']) ? (float) $payload['amount'] : null,
            sourceType: (string) ($payload['source_type'] ?? 'invoice'),
            sourceReference: $payload['source_reference'] ?? null,
            bookingId: isset($payload['booking_id']) ? (int) $payload['booking_id'] : null,
            notes: $payload['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'invoice_id' => $this->invoiceId,
            'amount' => $this->amount,
            'source_type' => $this->sourceType,
            'source_reference' => $this->sourceReference,
            'booking_id' => $this->bookingId,
            'notes' => $this->notes,
        ];
    }
}
