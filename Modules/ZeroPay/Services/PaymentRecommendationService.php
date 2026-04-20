<?php

namespace Modules\ZeroPay\Services;

class PaymentRecommendationService
{
    public function __construct(private readonly PaymentRailResolverService $resolver)
    {
    }

    public function recommendNextRail(array $availableMethods, float $amount): ?string
    {
        foreach ($availableMethods as $method) {
            $resolved = $this->resolver->resolve((string) $method, $amount);

            if ($resolved['rail_type'] === 'zero_fee') {
                return (string) $method;
            }
        }

        return $availableMethods[0] ?? null;
    }

    public function recommendNextChannel(array $channels): ?string
    {
        $priority = ['email', 'sms', 'whatsapp', 'messenger', 'telegram', 'voice'];

        foreach ($priority as $channel) {
            if (in_array($channel, $channels, true)) {
                return $channel;
            }
        }

        return $channels[0] ?? null;
    }
}
