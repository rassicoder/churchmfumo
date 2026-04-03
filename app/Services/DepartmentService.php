<?php

namespace App\Services;

use App\Models\Department;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class DepartmentService
{
    public function __construct(private readonly DepartmentRepositoryInterface $departments)
    {
    }

    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->departments->paginateWithFilters($filters, $perPage);
    }

    public function create(array $data): Department
    {
        $this->ensureLeaderBelongsToChurch($data);

        /** @var Department $department */
        $department = $this->departments->create($data);

        return $department->load(['church:id,name,status', 'leader:id,full_name,position,status']);
    }

    public function show(string $id): ?Department
    {
        /** @var Department|null $department */
        $department = $this->departments->findById($id);

        return $department?->load(['church:id,name,status', 'leader:id,full_name,position,status']);
    }

    public function update(string $id, array $data): ?Department
    {
        if (isset($data['church_id']) || array_key_exists('leader_id', $data)) {
            /** @var Department|null $existing */
            $existing = $this->departments->findById($id);

            if ($existing) {
                $resolvedData = array_merge([
                    'church_id' => $existing->church_id,
                    'leader_id' => $existing->leader_id,
                ], $data);

                $this->ensureLeaderBelongsToChurch($resolvedData);
            }
        }

        /** @var Department|null $department */
        $department = $this->departments->update($id, $data);

        return $department?->load(['church:id,name,status', 'leader:id,full_name,position,status']);
    }

    public function delete(string $id): bool
    {
        $department = $this->departments->findById($id);

        if (! $department) {
            return false;
        }

        if ($this->hasActiveProjects($department->id)) {
            throw ValidationException::withMessages([
                'department' => ['Department cannot be deleted because it has active projects.'],
            ]);
        }

        return (bool) $department->delete();
    }

    private function hasActiveProjects(string $departmentId): bool
    {
        $projectTable = config('department.project_table', 'projects');

        if (! Schema::hasTable($projectTable)) {
            return false;
        }

        if (! Schema::hasColumn($projectTable, 'department_id') || ! Schema::hasColumn($projectTable, 'status')) {
            return false;
        }

        return DB::table($projectTable)
            ->where('department_id', $departmentId)
            ->whereIn('status', config('department.active_project_statuses', ['active']))
            ->exists();
    }

    private function ensureLeaderBelongsToChurch(array $data): void
    {
        if (empty($data['leader_id']) || empty($data['church_id'])) {
            return;
        }

        $leaderInChurch = DB::table('leaders')
            ->where('id', $data['leader_id'])
            ->where('church_id', $data['church_id'])
            ->whereNull('deleted_at')
            ->exists();

        if (! $leaderInChurch) {
            throw ValidationException::withMessages([
                'leader_id' => ['Selected leader must belong to the selected church.'],
            ]);
        }
    }
}
