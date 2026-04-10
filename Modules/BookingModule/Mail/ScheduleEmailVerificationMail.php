<?php

namespace Modules\BookingModule\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Entities\Schedule;

class ScheduleEmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Schedule $schedule, public string $verifyUrl)
    {
    }

    public function build()
    {
        return $this->subject(__('bookingmodule::public.verify_email_subject'))
            ->view('bookingmodule::emails.schedule_verify_email')
            ->with([
                'schedule' => $this->schedule,
                'verifyUrl' => $this->verifyUrl,
            ]);
    }
}
