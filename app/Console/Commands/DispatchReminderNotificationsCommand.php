<?php

namespace App\Console\Commands;

use App\Jobs\SendActionDeadlineReminderJob;
use App\Jobs\SendLeaderTermExpiryReminderJob;
use App\Models\ActionItem;
use App\Models\Leader;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DispatchReminderNotificationsCommand extends Command
{
    protected $signature = 'notifications:dispatch-reminders';

    protected $description = 'Dispatch queued email reminders for action deadlines and term expiry';

    public function handle(): int
    {
        $this->dispatchActionDeadlineReminders();
        $this->dispatchTermExpiryReminders();

        $this->info('Reminder notification jobs dispatched successfully.');

        return self::SUCCESS;
    }

    private function dispatchActionDeadlineReminders(): void
    {
        $days = (int) config('meeting.deadline_reminder_days_before', 3);
        $targetDate = Carbon::today()->addDays($days)->toDateString();

        $actionItems = ActionItem::query()
            ->whereDate('deadline', $targetDate)
            ->whereIn('status', config('meeting.open_action_statuses', ['pending', 'in_progress']))
            ->pluck('id');

        foreach ($actionItems as $actionItemId) {
            SendActionDeadlineReminderJob::dispatch($actionItemId);
        }
    }

    private function dispatchTermExpiryReminders(): void
    {
        $daysBefore = config('leader.term_expiry_reminder_days_before', [30, 7, 3]);

        foreach ($daysBefore as $days) {
            $targetDate = Carbon::today()->addDays((int) $days)->toDateString();

            $leaders = Leader::query()
                ->whereDate('term_end', $targetDate)
                ->where('status', 'active')
                ->pluck('id');

            foreach ($leaders as $leaderId) {
                SendLeaderTermExpiryReminderJob::dispatch($leaderId);
            }
        }
    }
}
