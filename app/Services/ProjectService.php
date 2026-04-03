<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Services\DashboardCacheService;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects,
        private readonly DashboardCacheService $dashboardCache
    )
    {
    }

    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->projects->paginateWithFilters($filters, $perPage);
    }

    public function create(array $data): Project
    {
        $data = $this->applyDuration($data);

        if (! isset($data['status'])) {
            $data['status'] = config('project.default_status', 'planned');
        }

        /** @var Project $project */
        $project = $this->projects->create($data);

        $this->dashboardCache->invalidateAssociation();
        if (! empty($project->church_id)) {
            $this->dashboardCache->invalidateChurch($project->church_id);
        }
        return $project->load(['church:id,name,status', 'leader:id,full_name,position,status']);
    }

    public function show(string $id): ?Project
    {
        /** @var Project|null $project */
        $project = $this->projects->findById($id);

        return $project?->load(['church:id,name,status', 'leader:id,full_name,position,status']);
    }

    public function update(string $id, array $data): ?Project
    {
        if (isset($data['start_date']) || isset($data['end_date'])) {
            $data = $this->applyDuration($data, $id);
        }

        /** @var Project|null $project */
        $project = $this->projects->update($id, $data);

        if ($project) {
            $this->dashboardCache->invalidateAssociation();
            $this->dashboardCache->invalidateChurch($project->church_id);
        }

        return $project?->load(['church:id,name,status', 'leader:id,full_name,position,status']);
    }

    public function delete(string $id): bool
    {
        /** @var Project|null $project */
        $project = $this->projects->findById($id);
        $deleted = $this->projects->delete($id);

        if ($deleted && $project) {
            $this->dashboardCache->invalidateAssociation();
            $this->dashboardCache->invalidateChurch($project->church_id);
        }

        return $deleted;
    }

    public function dashboardSummary(array $filters = []): array
    {
        return $this->projects->dashboardSummary($filters);
    }

    private function applyDuration(array $data, ?string $projectId = null): array
    {
        $start = $data['start_date'] ?? null;
        $end = $data['end_date'] ?? null;

        if (! $start || ! $end) {
            if ($projectId) {
                /** @var Project|null $project */
                $project = $this->projects->findById($projectId);
                $start = $start ?: $project?->start_date;
                $end = $end ?: $project?->end_date;
            }
        }

        if (! $start || ! $end) {
            return $data;
        }

        $startDate = Carbon::parse($start)->startOfDay();
        $endDate = Carbon::parse($end)->startOfDay();
        $data['duration_days'] = max(0, $startDate->diffInDays($endDate) + 1);

        return $data;
    }
}
