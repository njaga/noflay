<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demoRequest;

    public function __construct($demoRequest)
    {
        $this->demoRequest = $demoRequest;
    }

    public function build()
    {
        return $this->from('sunu-stock@ndiagandiaye.com', 'Equipe Noflay')
                    ->subject('Demande de démo')
                    ->view('emails.demo-request')
                    ->with('demoRequest', $this->demoRequest);
    }
}
