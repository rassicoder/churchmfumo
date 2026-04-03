<?php

namespace App\Services;

use App\Models\Expense;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Services\DashboardCacheService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepositoryInterface $expenses,
        private readonly DashboardCacheService $dashboardCache
    )
    {
    }

    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->expenses->paginateWithFilters($filters, $perPage);
    }

    public function create(array $data): Expense
    {
        /** @var Expense $expense */
        $expense = $this->expenses->create($data);
        $this->invalidateDashboardFromProject($expense->project_id, $expense->date);

        return $expense->load(['project:id,church_id,department_id,name,status', 'approver:id,name,email']);
    }

    public function show(string $id): ?Expense
    {
        /** @var Expense|null $expense */
        $expense = $this->expenses->findById($id);

        return $expense?->load(['project:id,church_id,department_id,name,status', 'approver:id,name,email']);
    }

    public function update(string $id, array $data): ?Expense
    {
        /** @var Expense|null $expense */
        $expense = $this->expenses->update($id, $data);
        if ($expense) {
            $this->invalidateDashboardFromProject($expense->project_id, $expense->date);
        }

        return $expense?->load(['project:id,church_id,department_id,name,status', 'approver:id,name,email']);
    }

    public function delete(string $id): bool
    {
        /** @var Expense|null $expense */
        $expense = $this->expenses->findById($id);
        $deleted = $this->expenses->delete($id);

        if ($deleted && $expense) {
            $this->invalidateDashboardFromProject($expense->project_id, $expense->date);
        }

        return $deleted;
    }

    private function invalidateDashboardFromProject(string $projectId, $date): void
    {
        $project = DB::table('projects')->select(['id', 'church_id'])->where('id', $projectId)->first();
        if (! $project) {
            return;
        }

        $year = $date ? Carbon::parse($date)->year : (int) Carbon::now()->year;

        $this->dashboardCache->invalidateAssociation($year);
        $this->dashboardCache->invalidateChurch($project->church_id, $year);
    }
}
