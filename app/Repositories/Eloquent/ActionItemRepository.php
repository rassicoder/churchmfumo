<?php

namespace App\Repositories\Eloquent;

use App\Models\ActionItem;
use App\Repositories\Contracts\ActionItemRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActionItemRepository extends BaseRepository implements ActionItemRepositoryInterface
{
    public function __construct(ActionItem $actionItem)
    {
        parent::__construct($actionItem);
    }

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with([
            'meeting:id,church_id,meeting_type,meeting_date',
            'responsibleLeader:id,church_id,full_name,position,email,status',
        ]);

        if (! empty($filters['meeting_id'])) {
            $query->where('meeting_id', $filters['meeting_id']);
        }

        if (! empty($filters['church_id'])) {
            $query->whereHas('meeting', function ($meetingQuery) use ($filters): void {
                $meetingQuery->where('church_id', $filters['church_id']);
            });
        }

        if (! empty($filters['responsible_leader_id'])) {
            $query->where('responsible_leader_id', $filters['responsible_leader_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['overdue_only']) && filter_var($filters['overdue_only'], FILTER_VALIDATE_BOOLEAN)) {
            $query->whereDate('deadline', '<', Carbon::today()->toDateString())
                ->whereIn('status', config('meeting.open_action_statuses', ['pending', 'in_progress']));
        }

        return $query->orderBy('deadline')->paginate($perPage)->withQueryString();
    }
}
