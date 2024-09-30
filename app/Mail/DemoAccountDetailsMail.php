<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoAccountDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demoRequest;
    public $password;

    public function __construct($demoRequest, $password)
    {
        $this->demoRequest = $demoRequest;
        $this->password = $password;
    }

    public function build()
    {
        return $this->from('sunu-stock@ndiagandiaye.com', 'Equipe Noflay')
                    ->subject('Détails de votre compte de démonstration')
                    ->view('emails.demo-account-details')
                    ->with(['demoRequest' => $this->demoRequest, 'password' => $this->password]);
    }
}
