<?php

namespace App\Notifications;

use App\Models\ActionItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActionItemAssignedNotification extends Notification implements ShouldQueue
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
        $meeting = $this->actionItem->meeting;

        return (new MailMessage)
            ->subject('New Action Item Assigned')
            ->greeting('Hello '.$notifiable->full_name.',')
            ->line('A new action item has been assigned to you.')
            ->line('Description: '.$this->actionItem->description)
            ->line('Meeting Type: '.($meeting?->meeting_type ?? 'N/A'))
            ->line('Meeting Date: '.($meeting?->meeting_date?->toDateString() ?? 'N/A'))
            ->line('Deadline: '.($this->actionItem->deadline?->toDateString() ?? 'N/A'))
            ->line('Please review and complete it on time.');
    }
}
