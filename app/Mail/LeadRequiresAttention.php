<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadRequiresAttention extends Mailable
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
    public function build(): LeadRequiresAttention
    {
        return $this
            ->to('leads@eduopinions.com')
            ->from('support@eduopinions.com')
            ->subject("Lead {$this->deal['person_name']} required attention")
            ->markdown('emails.notes.lead-requires-attention');
    }
}
