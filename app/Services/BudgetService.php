<?php

namespace App\Services;

use App\Models\Budget;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Services\DashboardCacheService;

class BudgetService
{
    public function __construct(
        private readonly BudgetRepositoryInterface $budgets,
        private readonly DashboardCacheService $dashboardCache
    )
    {
    }

    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->budgets->paginateWithFilters($filters, $perPage);
    }

    public function create(array $data): Budget
    {
        /** @var Budget $budget */
        $budget = $this->budgets->create($data);
        $this->invalidateDashboard($budget->church_id, $budget->year);

        return $budget->load(['church:id,name,status', 'department:id,name', 'approver:id,name,email']);
    }

    public function show(string $id): ?Budget
    {
        /** @var Budget|null $budget */
        $budget = $this->budgets->findById($id);

        return $budget?->load(['church:id,name,status', 'department:id,name', 'approver:id,name,email']);
    }

    public function update(string $id, array $data): ?Budget
    {
        /** @var Budget|null $budget */
        $budget = $this->budgets->update($id, $data);
        if ($budget) {
            $this->invalidateDashboard($budget->church_id, $budget->year);
        }

        return $budget?->load(['church:id,name,status', 'department:id,name', 'approver:id,name,email']);
    }

    public function delete(string $id): bool
    {
        /** @var Budget|null $budget */
        $budget = $this->budgets->findById($id);
        $deleted = $this->budgets->delete($id);

        if ($deleted && $budget) {
            $this->invalidateDashboard($budget->church_id, $budget->year);
        }

        return $deleted;
    }

    public function financialSummary(array $filters = []): array
    {
        return $this->budgets->financialSummary($filters);
    }

    private function invalidateDashboard(string $churchId, int $year): void
    {
        $this->dashboardCache->invalidateAssociation($year);
        $this->dashboardCache->invalidateChurch($churchId, $year);
    }
}
