<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use stdClass;

class MessageForSales extends Mailable
{
    use Queueable, SerializesModels;

    private stdClass $deal;

    /**
     * Create a new message instance.
     *
     * @param stdClass $deal
     */
    public function __construct(stdClass $deal)
    {
        $this->deal = $deal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): MessageForSales
    {
        return $this
            ->to(['leads@eduopinions.com'])
            ->subject("Previous lead’s new info")
            ->markdown('emails.notes.message-for-sales-lead-generation-form', [
                'deal' => $this->deal
            ]);
    }
}
