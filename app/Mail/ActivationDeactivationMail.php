<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationDeactivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email_details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email_details)
    {
        $this->email_details = $email_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.activationDeactivationEmail')->with('email_details', $this->email_details);
    }
}
