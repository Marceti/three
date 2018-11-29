<?php

namespace App\Mail\Authentication;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class resetPasswordConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $url;
    private $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url,$password)
    {

        $this->url = $url;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.authentication.resetPasswordConfirmation')->with(['url'=>$this->url,'password'=>$this->password]);
    }
}
