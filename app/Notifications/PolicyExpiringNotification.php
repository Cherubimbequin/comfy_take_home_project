<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PolicyExpiringNotification extends Notification
{
    use Queueable;

    protected $policy;

    /**
     * Create a new notification instance.
     */
    public function __construct($policy)
    {
        $this->policy = $policy;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail']; 
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Policy is Expiring Soon')
            ->greeting("Hello, {$notifiable->name}")
            ->line("Your policy ({$this->policy->policy_number}) is expiring on {$this->policy->end_date->format('M d, Y')}.")
            ->line('Thank you for choosing our services!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'policy_id' => $this->policy->id,
            'policy_number' => $this->policy->policy_number,
            'end_date' => $this->policy->end_date,
        ];
    }
}
