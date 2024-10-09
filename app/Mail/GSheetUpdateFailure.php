<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GSheetUpdateFailure extends Mailable
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
    public function build(): GSheetUpdateFailure
    {
        return $this
            ->to('leads@eduopinions.com')
            ->subject("GSheet updating failure for deal #{$this->deal['id']}")
            ->markdown('emails.failure.vpa-gsheet');
    }
}
