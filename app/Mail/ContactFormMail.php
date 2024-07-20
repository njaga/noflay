<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contactForm;

    public function __construct($contactForm)
    {
        $this->contactForm = $contactForm;
    }

    public function build()
    {
        return $this->from('sunu-stock@ndiagandiaye.com', 'Equipe Noflay')
                    ->subject('Nouveau message de contact')
                    ->view('emails.contact-form')
                    ->with('contactForm', $this->contactForm);
    }
}
