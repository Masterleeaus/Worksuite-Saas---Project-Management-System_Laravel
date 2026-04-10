<?php

namespace Modules\CustomerConnect\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerConnectCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $bodyText
    ) {}

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('customerconnect::emails.generic')
            ->with(['bodyText' => $this->bodyText]);
    }
}
