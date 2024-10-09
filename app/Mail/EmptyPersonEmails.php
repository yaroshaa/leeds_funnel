<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmptyPersonEmails extends Mailable
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
    public function build(): EmptyPersonEmails
    {
        return $this
            ->to('leads@eduopinions.com')
            ->subject("Sending email failure for deal #{$this->deal['id']}. Empty emails")
            ->markdown('emails.failure.empty-emails');
    }
}
