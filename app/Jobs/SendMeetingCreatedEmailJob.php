<?php

namespace App\Jobs;

use App\Models\Meeting;
use App\Notifications\MeetingCreatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMeetingCreatedEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly string $meetingId)
    {
    }

    public function handle(): void
    {
        $meeting = Meeting::query()
            ->with(['church:id,name,pastor_id', 'church.pastor:id,name,email', 'creator:id,name,email'])
            ->find($this->meetingId);

        if (! $meeting) {
            return;
        }

        $recipients = collect([$meeting->creator, $meeting->church?->pastor])
            ->filter(fn ($recipient) => $recipient && ! empty($recipient->email))
            ->unique('id');

        foreach ($recipients as $recipient) {
            $recipient->notify(new MeetingCreatedNotification($meeting));
        }
    }
}
