<?php

namespace Modules\BookingModule\Listeners;

use Modules\BookingModule\Entities\BookingModuleSetting;

class CompanyCreatedListener
{
    public function handle($event): void
    {
        if (!isset($event->company)) {
            return;
        }

        BookingModuleSetting::addModuleSetting($event->company);
    }
}
