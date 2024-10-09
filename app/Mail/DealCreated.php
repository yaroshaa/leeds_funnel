<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use stdClass;

class DealCreated extends Mailable
{
    use Queueable, SerializesModels;

    public object $data;
    private stdClass $deal;

    /**
     * Create a new message instance.
     *
     * @param string $data
     * @param stdClass $deal
     */
    public function __construct(string $data, stdClass $deal)
    {
        $this->data = json_decode($data);
        $this->deal = $deal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): DealCreated
    {
        return $this
            ->to(['jordi@eduopinions.com', 'nikos@eduopinions.com'])
            ->subject("VPA (C. 0): {$this->data->discipline}")
            ->markdown('emails.notes.deal-created', [
                'data' => $this->data,
                'deal' => $this->deal
            ]);
    }
}
