<?php

namespace Modules\TitanGo\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\FSMCore\Models\FSMOrder;

/**
 * ProofOfDeliveryMail
 *
 * Sends the Proof-of-Delivery PDF to a given recipient email.
 * The PDF content is passed as a raw binary string (already rendered by
 * the dompdf.wrapper) so the mailable itself has no dompdf dependency.
 */
class ProofOfDeliveryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly FSMOrder $order,
        private readonly string  $pdfContent,
        private readonly string  $filename,
    ) {}

    public function build(): static
    {
        return $this
            ->subject('Proof of Delivery — ' . $this->order->name)
            ->view('titango::mail.proof_of_delivery')
            ->attachData($this->pdfContent, $this->filename, [
                'mime' => 'application/pdf',
            ]);
    }
}
