<?php

namespace App\Repositories\Eloquent;

use App\Models\ActionItem;
use App\Models\Meeting;
use App\Repositories\Contracts\MeetingRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MeetingRepository extends BaseRepository implements MeetingRepositoryInterface
{
    public function __construct(Meeting $meeting)
    {
        parent::__construct($meeting);
    }

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['church:id,name,status', 'creator:id,name,email']);

        if (! empty($filters['church_id'])) {
            $query->where('church_id', $filters['church_id']);
        }

        if (! empty($filters['meeting_type'])) {
            $query->where('meeting_type', $filters['meeting_type']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('meeting_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('meeting_date', '<=', $filters['date_to']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('meeting_type', 'like', '%'.$search.'%')
                    ->orWhere('agenda', 'like', '%'.$search.'%')
                    ->orWhere('minutes', 'like', '%'.$search.'%')
                    ->orWhereHas('church', fn ($churchQuery) => $churchQuery->where('name', 'like', '%'.$search.'%'));
            });
        }

        return $query->orderByDesc('meeting_date')->paginate($perPage)->withQueryString();
    }

    public function dashboardSummary(array $filters = []): array
    {
        $today = Carbon::today()->toDateString();
        $meetingQuery = $this->model->newQuery();

        if (! empty($filters['church_id'])) {
            $meetingQuery->where('church_id', $filters['church_id']);
        }

        $meetingIds = (clone $meetingQuery)->pluck('id');

        $actionQuery = ActionItem::query()->whereIn('meeting_id', $meetingIds);

        return [
            'meetings_total' => (clone $meetingQuery)->count(),
            'meetings_upcoming' => (clone $meetingQuery)->whereDate('meeting_date', '>=', $today)->count(),
            'meetings_this_month' => (clone $meetingQuery)
                ->whereMonth('meeting_date', Carbon::today()->month)
                ->whereYear('meeting_date', Carbon::today()->year)
                ->count(),
            'action_items_total' => (clone $actionQuery)->count(),
            'action_items_overdue' => (clone $actionQuery)
                ->whereDate('deadline', '<', $today)
                ->whereIn('status', config('meeting.open_action_statuses', ['pending', 'in_progress']))
                ->count(),
            'action_items_completed' => (clone $actionQuery)->where('status', 'completed')->count(),
        ];
    }
}
