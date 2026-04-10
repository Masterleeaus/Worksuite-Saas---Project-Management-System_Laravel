<?php

namespace Modules\CustomerConnect\Services\Channels;

class ChannelSenderManager
{
    public function __construct(
        protected ChannelSenderInterface $defaultSender
    ) {}

    public function sender(): ChannelSenderInterface
    {
        return $this->defaultSender;
    }
}
