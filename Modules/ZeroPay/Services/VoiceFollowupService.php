<?php

namespace Modules\ZeroPay\Services;

use Modules\ZeroPay\Actions\QueueVoiceFollowupAction;
use Modules\ZeroPay\Models\ZeroPaySession;

class VoiceFollowupService
{
    public function __construct(private readonly ?QueueVoiceFollowupAction $queueAction = null)
    {
    }

    public function queue(ZeroPaySession $session)
    {
        $action = $this->queueAction ?? app(QueueVoiceFollowupAction::class);

        return $action->execute($session);
    }
}
