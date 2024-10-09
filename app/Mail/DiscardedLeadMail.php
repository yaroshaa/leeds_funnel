<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DiscardedLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    private array $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): DiscardedLeadMail
    {
        return $this
            ->to($this->data['email'])
            ->subject("Thank you for contacting EDUopinions")
            ->markdown('emails.notes.message-for-discarded-lead', [
                'data' => $this->data
            ]);
    }
}
