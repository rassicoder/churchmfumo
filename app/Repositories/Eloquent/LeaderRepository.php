<?php

namespace App\Repositories\Eloquent;

use App\Models\Leader;
use App\Repositories\Contracts\LeaderRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaderRepository extends BaseRepository implements LeaderRepositoryInterface
{
    public function __construct(Leader $leader)
    {
        parent::__construct($leader);
    }

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('church:id,name,status');

        if (! empty($filters['church_id'])) {
            $query->where('church_id', $filters['church_id']);
        }

        if (! empty($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $search = $filters['search'] ?? $filters['q'] ?? null;
        if (! empty($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('full_name', 'like', '%'.$search.'%')
                    ->orWhere('position', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }

        if (! empty($filters['term_state'])) {
            $today = Carbon::today()->toDateString();
            $expiringLimit = Carbon::today()
                ->addDays((int) config('leader.expiring_within_days', 30))
                ->toDateString();

            if ($filters['term_state'] === 'expired') {
                $query->whereDate('term_end', '<', $today);
            }

            if ($filters['term_state'] === 'expiring') {
                $query->whereDate('term_end', '>=', $today)
                    ->whereDate('term_end', '<=', $expiringLimit);
            }

            if ($filters['term_state'] === 'active') {
                $query->where(function ($activeQuery) use ($today, $expiringLimit): void {
                    $activeQuery->whereNull('term_end')
                        ->orWhereDate('term_end', '>', $expiringLimit);
                });
            }
        }

        return $query
            ->orderByDesc('term_end')
            ->orderBy('full_name')
            ->paginate($perPage)
            ->withQueryString();
    }
}
