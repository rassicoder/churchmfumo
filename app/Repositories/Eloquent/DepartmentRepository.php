<?php

namespace App\Repositories\Eloquent;

use App\Models\Department;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct(Department $department)
    {
        parent::__construct($department);
    }

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model
            ->newQuery()
            ->with(['church:id,name,status', 'leader:id,full_name,position,status']);

        if (! empty($filters['church_id'])) {
            $query->where('church_id', $filters['church_id']);
        }

        if (! empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (! empty($filters['leader_id'])) {
            $query->where('leader_id', $filters['leader_id']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhereHas('church', fn ($churchQuery) => $churchQuery->where('name', 'like', '%'.$search.'%'))
                    ->orWhereHas('leader', fn ($leaderQuery) => $leaderQuery->where('full_name', 'like', '%'.$search.'%'));
            });
        }

        return $query
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }
}
