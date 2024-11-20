<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PolicyCreatedNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $buyer;
    public $policy;

    public function __construct($buyer, $policy)
    {
        $this->buyer = $buyer;
        $this->policy = $policy;
    }

    public function build()
    {
        return $this->subject('Your Policy Has Been Purchased!')
            ->view('emails.policy-created')
            ->with([
                'buyerName' => $this->buyer->name,
                'policyNumber' => $this->policy->policy_number,
            ]);
    }
}
