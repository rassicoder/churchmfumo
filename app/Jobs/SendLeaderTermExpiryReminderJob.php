<?php

namespace App\Jobs;

use App\Models\Leader;
use App\Notifications\LeaderTermExpiryReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLeaderTermExpiryReminderJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly string $leaderId)
    {
    }

    public function handle(): void
    {
        $leader = Leader::query()->find($this->leaderId);

        if (! $leader || empty($leader->email)) {
            return;
        }

        $leader->notify(new LeaderTermExpiryReminderNotification($leader));
    }
}
