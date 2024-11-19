<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PolicyPurchasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $policy;

    public function __construct($user, $policy)
    {
        $this->user = $user;
        $this->policy = $policy;
    }

    public function build()
    {
        return $this->subject('Congratulations on Your Policy Purchase!')
                    ->view('emails.policy-purchased')
                    ->with([
                        'userName' => $this->user->name,
                        'policyNumber' => $this->policy->policy_number,
                        'startDate' => $this->policy->start_date,
                        'endDate' => $this->policy->end_date,
                    ]);
    }
}
