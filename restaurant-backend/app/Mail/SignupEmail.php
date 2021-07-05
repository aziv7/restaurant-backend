<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignupEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->email_data = $data;//var_dump($this->email_data);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       // var_dump('email ownere'); var_dump(env('MAIL_USERNAME'));
      //  var_dump('email receiver'); var_dump($this->email_data);
        return $this->from('msfoodverify@msdigital34.fr', 'Team')->subject("Welcome to our restaurent!")->view('mail.signup-mail', ['email_data' => $this->email_data]);
    }
}
