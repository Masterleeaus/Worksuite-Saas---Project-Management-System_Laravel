<?php

namespace Modules\ClientPulse\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\ClientPulse\Listeners\SendPostJobRatingPrompt;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function boot(): void
    {
        parent::boot();

        // Listen to the CleaningJobCompleteEvent fired by the Sms module observer
        // when an FSMOrder stage is set to a completion stage.
        // We call Event::listen() directly (not via $this->listen) because parent::boot()
        // has already processed the $listen array before we reach this point.
        if (class_exists(\Modules\Sms\Events\CleaningJobCompleteEvent::class)) {
            Event::listen(
                \Modules\Sms\Events\CleaningJobCompleteEvent::class,
                SendPostJobRatingPrompt::class
            );
        }
    }
}
