<?php

namespace App\Notifications;

use App\Models\Leader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaderTermExpiryReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Leader $leader)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Leadership Term Expiry Reminder')
            ->greeting('Hello '.$this->leader->full_name.',')
            ->line('Your leadership term is approaching expiry.')
            ->line('Position: '.$this->leader->position)
            ->line('Term End Date: '.optional($this->leader->term_end)?->toDateString())
            ->line('Please coordinate with your church administration for next steps.');
    }
}
