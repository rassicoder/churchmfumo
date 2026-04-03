<?php

namespace App\Services;

use App\Repositories\Contracts\ActivityLogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityLogService
{
    public function __construct(private readonly ActivityLogRepositoryInterface $activityLogs)
    {
    }

    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->activityLogs->paginateWithFilters($filters, $perPage);
    }
}
