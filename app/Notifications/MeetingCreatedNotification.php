<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Meeting $meeting)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Meeting Created')
            ->greeting('Hello,')
            ->line('A new meeting has been created.')
            ->line('Meeting Type: '.$this->meeting->meeting_type)
            ->line('Meeting Date: '.optional($this->meeting->meeting_date)?->toDateString())
            ->line('Agenda: '.($this->meeting->agenda ?: 'N/A'));
    }
}
