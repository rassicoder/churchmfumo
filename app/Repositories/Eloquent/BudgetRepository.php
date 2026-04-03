<?php

namespace App\Repositories\Eloquent;

use App\Models\Budget;
use App\Models\Expense;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BudgetRepository extends BaseRepository implements BudgetRepositoryInterface
{
    public function __construct(Budget $budget)
    {
        parent::__construct($budget);
    }

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['church:id,name,status', 'department:id,name', 'approver:id,name,email']);

        if (! empty($filters['church_id'])) {
            $query->where('church_id', $filters['church_id']);
        }

        if (! empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (! empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderByDesc('year')->paginate($perPage)->withQueryString();
    }

    public function financialSummary(array $filters = []): array
    {
        $budgetsQuery = $this->model->newQuery();

        if (! empty($filters['church_id'])) {
            $budgetsQuery->where('church_id', $filters['church_id']);
        }

        if (! empty($filters['year'])) {
            $budgetsQuery->where('year', $filters['year']);
        }

        $budgetIds = (clone $budgetsQuery)->pluck('id');

        $projectsQuery = DB::table('projects')
            ->select('projects.id', 'projects.church_id', 'projects.department_id', 'projects.status')
            ->when(! empty($filters['church_id']), function ($q) use ($filters) {
                $q->where('projects.church_id', $filters['church_id']);
            });

        $expensesQuery = Expense::query()
            ->join('projects', 'projects.id', '=', 'expenses.project_id')
            ->whereIn('projects.church_id', $projectsQuery->pluck('church_id')->unique()->toArray());

        if (! empty($filters['year'])) {
            $expensesQuery->whereYear('expenses.date', $filters['year']);
        }

        $totalAllocated = (clone $budgetsQuery)->sum('allocated_amount');
        $totalExpenses = (clone $expensesQuery)->sum('expenses.amount');

        return [
            'total_allocated' => (float) $totalAllocated,
            'total_spent' => (float) $totalExpenses,
            'variance' => (float) ($totalAllocated - $totalExpenses),
            'budgets_count' => (clone $budgetsQuery)->count(),
        ];
    }
}
