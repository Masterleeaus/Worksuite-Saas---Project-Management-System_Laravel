<?php

namespace Modules\ClientPulse\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RatingPromptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly object $order,
        public readonly object $client,
        public readonly string $ratingUrl,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('How did we do? Rate your recent clean')
            ->view('clientpulse::emails.rating_prompt');
    }
}
