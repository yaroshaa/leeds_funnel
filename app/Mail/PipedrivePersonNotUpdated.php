<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PipedrivePersonNotUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public array $deal;

    /**
     * Create a new message instance.
     *
     * @param array $deal
     */
    public function __construct(array $deal)
    {
        $this->deal = $deal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): PipedrivePersonNotUpdated
    {
        return $this
            ->to('leads@eduopinions.com')
            ->subject("Updating person of deal #{$this->deal['id']} failure")
            ->markdown('emails.failure.pipedrive-person');
    }
}
