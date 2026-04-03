<?php

namespace App\Services;

use App\Jobs\SendActionAssignedEmailJob;
use App\Models\ActionItem;
use App\Models\Meeting;
use App\Repositories\Contracts\ActionItemRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use App\Services\DashboardCacheService;

class ActionItemService
{
    public function __construct(
        private readonly ActionItemRepositoryInterface $actionItems,
        private readonly DashboardCacheService $dashboardCache
    )
    {
    }

    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        $items = $this->actionItems->paginateWithFilters($filters, $perPage);

        $items->setCollection($items->getCollection()->map(fn (ActionItem $item): array => $this->transform($item)));

        return $items;
    }

    public function create(array $data): array
    {
        if (! isset($data['status'])) {
            $data['status'] = 'pending';
        }

        $this->validateAssignmentContext($data['meeting_id'], $data['responsible_leader_id']);

        /** @var ActionItem $item */
        $item = $this->actionItems->create($data);
        $item->load(['meeting:id,meeting_type,meeting_date,church_id', 'responsibleLeader:id,full_name,email']);

        $this->notifyAssignment($item);
        $this->invalidateDashboardForMeeting($item->meeting_id);

        return $this->transform($item);
    }

    public function show(string $id): ?array
    {
        /** @var ActionItem|null $item */
        $item = $this->actionItems->findById($id);

        if (! $item) {
            return null;
        }

        $item->load(['meeting:id,meeting_type,meeting_date,church_id', 'responsibleLeader:id,full_name,email']);

        return $this->transform($item);
    }

    public function update(string $id, array $data): ?array
    {
        /** @var ActionItem|null $item */
        $item = $this->actionItems->findById($id);

        if (! $item) {
            return null;
        }

        $resolvedMeetingId = $data['meeting_id'] ?? $item->meeting_id;
        $resolvedLeaderId = $data['responsible_leader_id'] ?? $item->responsible_leader_id;
        $this->validateAssignmentContext($resolvedMeetingId, $resolvedLeaderId);

        $previousLeaderId = $item->responsible_leader_id;

        /** @var ActionItem|null $updated */
        $updated = $this->actionItems->update($id, $data);
        $updated?->load(['meeting:id,meeting_type,meeting_date,church_id', 'responsibleLeader:id,full_name,email']);

        if ($updated && $updated->responsible_leader_id !== $previousLeaderId) {
            $this->notifyAssignment($updated);
        }

        if ($updated) {
            $this->invalidateDashboardForMeeting($updated->meeting_id);
        }

        return $updated ? $this->transform($updated) : null;
    }

    public function delete(string $id): bool
    {
        /** @var ActionItem|null $item */
        $item = $this->actionItems->findById($id);
        $deleted = $this->actionItems->delete($id);

        if ($deleted && $item) {
            $this->invalidateDashboardForMeeting($item->meeting_id);
        }

        return $deleted;
    }

    private function transform(ActionItem $item): array
    {
        $overdueMeta = $this->overdueMeta($item);

        return [
            'id' => $item->id,
            'meeting_id' => $item->meeting_id,
            'meeting' => $item->meeting,
            'description' => $item->description,
            'responsible_leader_id' => $item->responsible_leader_id,
            'responsible_leader' => $item->responsibleLeader,
            'deadline' => optional($item->deadline)?->toDateString(),
            'status' => $item->status,
            'is_overdue' => $overdueMeta['is_overdue'],
            'overdue_days' => $overdueMeta['overdue_days'],
            'highlight' => $overdueMeta['is_overdue'] ? 'overdue' : null,
            'created_at' => optional($item->created_at)?->toISOString(),
            'updated_at' => optional($item->updated_at)?->toISOString(),
        ];
    }

    private function overdueMeta(ActionItem $item): array
    {
        if (! $item->deadline || ! in_array($item->status, config('meeting.open_action_statuses', ['pending', 'in_progress']), true)) {
            return ['is_overdue' => false, 'overdue_days' => 0];
        }

        $today = Carbon::today();
        $deadline = Carbon::parse($item->deadline)->startOfDay();

        if ($deadline->gte($today)) {
            return ['is_overdue' => false, 'overdue_days' => 0];
        }

        return [
            'is_overdue' => true,
            'overdue_days' => $deadline->diffInDays($today),
        ];
    }

    private function notifyAssignment(ActionItem $item): void
    {
        SendActionAssignedEmailJob::dispatch($item->id);
    }

    private function validateAssignmentContext(string $meetingId, string $leaderId): void
    {
        $meeting = Meeting::query()->select(['id', 'church_id'])->find($meetingId);

        if (! $meeting) {
            throw ValidationException::withMessages([
                'meeting_id' => ['Meeting not found.'],
            ]);
        }

        $leader = \App\Models\Leader::query()
            ->select(['id', 'church_id'])
            ->where('id', $leaderId)
            ->whereNull('deleted_at')
            ->first();

        if (! $leader) {
            throw ValidationException::withMessages([
                'responsible_leader_id' => ['Responsible leader not found.'],
            ]);
        }

        if ($leader->church_id !== $meeting->church_id) {
            throw ValidationException::withMessages([
                'responsible_leader_id' => ['Responsible leader must belong to the same church as the meeting.'],
            ]);
        }
    }

    private function invalidateDashboardForMeeting(string $meetingId): void
    {
        $meeting = Meeting::query()->select(['id', 'church_id'])->find($meetingId);

        if (! $meeting) {
            return;
        }

        $this->dashboardCache->invalidateAssociation();
        $this->dashboardCache->invalidateChurch($meeting->church_id);
    }
}
