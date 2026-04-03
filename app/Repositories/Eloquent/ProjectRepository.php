<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $project)
    {
        parent::__construct($project);
    }

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['church:id,name,status', 'leader:id,full_name,position,status']);

        if (! empty($filters['church_id'])) {
            $query->where('church_id', $filters['church_id']);
        }

        if (! empty($filters['leader_id'])) {
            $query->where('leader_id', $filters['leader_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $search = $filters['search'] ?? $filters['q'] ?? null;
        if (! empty($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('start_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('end_date', '<=', $filters['date_to']);
        }

        return $query->orderByDesc('start_date')->paginate($perPage)->withQueryString();
    }

    public function dashboardSummary(array $filters = []): array
    {
        $query = $this->model->newQuery();

        if (! empty($filters['church_id'])) {
            $query->where('church_id', $filters['church_id']);
        }

        $statusCounts = (clone $query)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        return [
            'total_projects' => (clone $query)->count(),
            'total_budget' => (float) (clone $query)->sum('budget'),
            'average_progress' => (float) (clone $query)->avg('progress'),
            'by_status' => $statusCounts,
        ];
    }
}
