<?php

// App\Mail\LandlordAccountCreated.php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LandlordAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Votre nouveau compte de bailleur')
            ->view('emails.landlord_account_created')
            ->with([
                'email' => $this->user->email,
                'password' => $this->password,
            ]);
    }
}
