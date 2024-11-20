<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $recipientType;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $recipientType)
    {
        $this->data = $data;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        if ($this->recipientType === 'user') {
            return $this->subject('We Received Your Message')
                ->view('emails.contact-user');
        } else {
            return $this->subject('New Contact Form Submission')
                ->view('emails.contact-admin');
        }
    }
}
