<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PolicyExpiredNotification extends Notification
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
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Policy Has Expired')
            ->greeting("Hello, {$notifiable->name}")
            ->line("We want to inform you that your policy ({$this->policy->policy_number}) expired on {$this->policy->end_date->format('M d, Y')}.")
            ->line('If you wish to renew your policy, please visit the link below.')
            ->action('Renew Policy', url('/policy/buy/' . $this->policy->id))
            ->line('Thank you for choosing our services!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
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
