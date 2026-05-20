<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewQuoteRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Quote $quote)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Yeni teklif talebi: '.$this->quote->ref_code)
            ->view('emails.quotes.new-request');
    }
}
