<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class AvailableSlots extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Collection $slots) {}

    public function build()
    {
        return $this->markdown('emails.slots.found', [
            'slots' => $this->slots,
        ]);
    }
}
