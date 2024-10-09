<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PipedriveNoteNotCreated extends Mailable
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
    public function build()
    {
        return $this
            ->to('leads@eduopinions.com')
            ->subject("Note creation for deal #{$this->deal['id']} failure")
            ->markdown('emails.failure.pipedrive-note');
    }
}
