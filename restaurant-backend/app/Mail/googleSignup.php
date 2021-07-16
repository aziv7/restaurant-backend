<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class googleSignup extends Mailable
{
    use Queueable, SerializesModels;
    public $password;
    public $email;
    public $login;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($login,$password,$email)
    {
$this->password=$password;
$this->login=$login;
$this->email=$email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.google.signup')->with([
            'password' => $this->password,
            'email' => $this->email,
            'login'=>$this->login
        ]);
    }
}
