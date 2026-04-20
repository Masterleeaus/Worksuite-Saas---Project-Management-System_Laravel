<?php

namespace Modules\ZeroPay\Services;

use Modules\ZeroPay\Models\ZeroPayAttempt;
use Modules\ZeroPay\Models\ZeroPaySession;

class ZeroPaySessionService
{
    public function __construct(private readonly PaymentSessionService $service)
    {
    }

    public function createSession(array $payload): ZeroPaySession
    {
        return $this->service->create($payload);
    }

    public function selectMethod(ZeroPaySession $session, string $method): ZeroPayAttempt
    {
        return $this->service->selectMethod($session, $method);
    }

    public function queueEmailFollowup(ZeroPaySession $session, string $message = 'Invoice reminder sent by ZeroPay')
    {
        return $this->service->queueEmail($session, $message);
    }

    public function queueVoiceFollowup(ZeroPaySession $session)
    {
        return $this->service->queueVoice($session);
    }

    public function confirmAttempt(ZeroPayAttempt $attempt, array $payload = [])
    {
        return $this->service->confirmAttempt($attempt, $payload);
    }
}
