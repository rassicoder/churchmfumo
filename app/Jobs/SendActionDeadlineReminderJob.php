<?php

namespace App\Jobs;

use App\Models\ActionItem;
use App\Notifications\ActionItemDeadlineReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendActionDeadlineReminderJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly string $actionItemId)
    {
    }

    public function handle(): void
    {
        $actionItem = ActionItem::query()
            ->with(['meeting:id,meeting_type,meeting_date', 'responsibleLeader:id,full_name,email'])
            ->find($this->actionItemId);

        $leader = $actionItem?->responsibleLeader;

        if (! $actionItem || ! $leader || empty($leader->email)) {
            return;
        }

        $leader->notify(new ActionItemDeadlineReminderNotification($actionItem));
    }
}
