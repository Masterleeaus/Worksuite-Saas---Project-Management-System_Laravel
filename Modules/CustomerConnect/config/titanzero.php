<?php

/*
|--------------------------------------------------------------------------
| CustomerConnect — TitanZero Bridge Config
|--------------------------------------------------------------------------
| Registers this module's capabilities with the TitanZero orchestration layer.
| Loaded and registered in CustomerConnectServiceProvider::boot() if
| TitanZero is present.
*/

return [

    'module'      => 'CustomerConnect',
    'version'     => '5.0.0',
    'description' => 'Multi-channel campaign marketing, inbox management, and customer messaging',

    'voice_phrases' => [
        // Intent => route name or action
        'send campaign'           => 'customerconnect.campaigns.index',
        'check inbox'             => 'customerconnect.inbox.index',
        'check messages'          => 'customerconnect.inbox.index',
        'how many unread'         => ['action' => 'customerconnect.unread_count'],
        'how many unread messages'=> ['action' => 'customerconnect.unread_count'],
        'view campaigns'          => 'customerconnect.campaigns.index',
        'campaign overview'       => 'customerconnect.dashboard.index',
        'delivery health'         => 'customerconnect.health.index',
        'open threads'            => 'customerconnect.inbox.index',
        'manage suppressions'     => 'customerconnect.settings.suppressions.index',
    ],

    'capabilities' => [
        'inbox'     => true,
        'campaigns' => true,
        'audiences' => true,
        'webhooks'  => ['twilio', 'vonage', 'telegram'],
        'exports'   => true,
        'recipes'   => true,
        'sla'       => true,
    ],

];
