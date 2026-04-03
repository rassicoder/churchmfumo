<?php

namespace App\Services;

use App\Models\Leader;
use App\Repositories\Contracts\LeaderRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaderService
{
    public function __construct(private readonly LeaderRepositoryInterface $leaders)
    {
    }

    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        $leaders = $this->leaders->paginateWithFilters($filters, $perPage);

        $leaders->setCollection(
            $leaders->getCollection()->map(fn (Leader $leader): array => $this->transformLeader($leader))
        );

        return $leaders;
    }

    public function create(array $data): Leader
    {
        if (! isset($data['status'])) {
            $data['status'] = config('leader.default_status', 'active');
        }

        /** @var Leader $leader */
        $leader = $this->leaders->create($data);

        return $leader->load('church:id,name,status');
    }

    public function show(string $id): ?array
    {
        /** @var Leader|null $leader */
        $leader = $this->leaders->findById($id);

        if (! $leader) {
            return null;
        }

        $leader->load('church:id,name,status');

        return $this->transformLeader($leader);
    }

    public function update(string $id, array $data): ?Leader
    {
        /** @var Leader|null $leader */
        $leader = $this->leaders->update($id, $data);

        return $leader?->load('church:id,name,status');
    }

    public function delete(string $id): bool
    {
        return $this->leaders->delete($id);
    }

    public function profile(string $id): ?array
    {
        /** @var Leader|null $leader */
        $leader = $this->leaders->findById($id);

        if (! $leader) {
            return null;
        }

        $leader->load('church:id,name,location,status');
        $term = $this->termMeta($leader);

        return [
            'basic_info' => [
                'id' => $leader->id,
                'full_name' => $leader->full_name,
                'position' => $leader->position,
                'level' => $leader->level,
                'status' => $leader->status,
            ],
            'affiliation' => [
                'church' => $leader->church,
            ],
            'contacts' => [
                'phone' => $leader->phone,
                'email' => $leader->email,
            ],
            'term' => [
                'term_start' => optional($leader->term_start)?->toDateString(),
                'term_end' => optional($leader->term_end)?->toDateString(),
                'is_expired' => $term['is_expired'],
                'is_expiring_soon' => $term['is_expiring_soon'],
                'days_to_term_end' => $term['days_to_term_end'],
            ],
        ];
    }

    private function transformLeader(Leader $leader): array
    {
        $term = $this->termMeta($leader);

        return [
            'id' => $leader->id,
            'church_id' => $leader->church_id,
            'church' => $leader->church,
            'full_name' => $leader->full_name,
            'position' => $leader->position,
            'level' => $leader->level,
            'term_start' => optional($leader->term_start)?->toDateString(),
            'term_end' => optional($leader->term_end)?->toDateString(),
            'phone' => $leader->phone,
            'email' => $leader->email,
            'status' => $leader->status,
            'term_meta' => $term,
            'created_at' => optional($leader->created_at)?->toISOString(),
            'updated_at' => optional($leader->updated_at)?->toISOString(),
        ];
    }

    private function termMeta(Leader $leader): array
    {
        if (! $leader->term_end) {
            return [
                'is_expired' => false,
                'is_expiring_soon' => false,
                'days_to_term_end' => null,
            ];
        }

        $today = Carbon::today();
        $termEnd = Carbon::parse($leader->term_end)->startOfDay();
        $daysToTermEnd = $today->diffInDays($termEnd, false);

        $isExpired = $daysToTermEnd < 0;
        $isExpiringSoon = ! $isExpired && $daysToTermEnd <= (int) config('leader.expiring_within_days', 30);

        return [
            'is_expired' => $isExpired,
            'is_expiring_soon' => $isExpiringSoon,
            'days_to_term_end' => $daysToTermEnd,
        ];
    }
}
