<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPolicyNotificationMail extends Mailable
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
        return $this->subject('Policy Purchased Notification')
                    ->view('emails.admin-policy-notification')
                    ->with([
                        'buyerName' => $this->buyer->name,
                        'policyNumber' => $this->policy->policy_number,
                        'policyAmount' => $this->policy->premium_amount,
                    ]);
    }
}
