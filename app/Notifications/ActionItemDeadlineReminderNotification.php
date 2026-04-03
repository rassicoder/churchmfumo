<?php

namespace App\Notifications;

use App\Models\ActionItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActionItemDeadlineReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly ActionItem $actionItem)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Action Item Deadline Reminder')
            ->greeting('Hello '.$notifiable->full_name.',')
            ->line('Your assigned action item is due soon.')
            ->line('Description: '.$this->actionItem->description)
            ->line('Deadline: '.optional($this->actionItem->deadline)?->toDateString())
            ->line('Please complete this task before the deadline.');
    }
}
