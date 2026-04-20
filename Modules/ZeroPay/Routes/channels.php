<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('zeropay.session.{sessionId}', function ($user, $sessionId) {
    return (int) ($user->company_id ?? 0) > 0;
});
