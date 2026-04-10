<?php

namespace Modules\ClientPulse\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\ClientPulse\Listeners\SendPostJobRatingPrompt;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function boot(): void
    {
        parent::boot();

        // Listen to the CleaningJobCompleteEvent fired by the Sms module observer
        // when an FSMOrder stage is set to a completion stage.
        if (class_exists(\Modules\Sms\Events\CleaningJobCompleteEvent::class)) {
            $this->listen[\Modules\Sms\Events\CleaningJobCompleteEvent::class][] =
                SendPostJobRatingPrompt::class;
        }
    }
}
