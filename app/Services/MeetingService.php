<?php

namespace App\Services;

use App\Jobs\SendMeetingCreatedEmailJob;
use App\Models\Meeting;
use App\Repositories\Contracts\MeetingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MeetingService
{
    public function __construct(private readonly MeetingRepositoryInterface $meetings)
    {
    }

    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->meetings->paginateWithFilters($filters, $perPage);
    }

    public function create(array $data): Meeting
    {
        /** @var Meeting $meeting */
        $meeting = $this->meetings->create($data);
        $meeting->load(['church:id,name,status,pastor_id', 'church.pastor:id,name,email', 'creator:id,name,email']);

        SendMeetingCreatedEmailJob::dispatch($meeting->id);

        return $meeting;
    }

    public function show(string $id): ?Meeting
    {
        /** @var Meeting|null $meeting */
        $meeting = $this->meetings->findById($id);

        return $meeting?->load(['church:id,name,status', 'creator:id,name,email']);
    }

    public function update(string $id, array $data): ?Meeting
    {
        /** @var Meeting|null $meeting */
        $meeting = $this->meetings->update($id, $data);

        return $meeting?->load(['church:id,name,status', 'creator:id,name,email']);
    }

    public function delete(string $id): bool
    {
        return $this->meetings->delete($id);
    }

    public function dashboardSummary(array $filters = []): array
    {
        return $this->meetings->dashboardSummary($filters);
    }
}
