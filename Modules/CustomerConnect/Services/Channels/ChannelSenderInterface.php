<?php

namespace Modules\CustomerConnect\Services\Channels;

use Modules\CustomerConnect\Entities\Delivery;

interface ChannelSenderInterface
{
    /**
     * Send a campaign delivery (legacy entrypoint).
     */
    public function send(Delivery $delivery): SendResult;

    /**
     * Send a generic outbound message (Inbox / ad-hoc).
     * All provider-specific logic must remain inside the sender implementation.
     */
    public function sendOutbound(OutboundMessage $message): SendResult;
}
