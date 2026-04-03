<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardCacheService
{
    public function invalidateAssociation(?int $year = null, ?int $month = null): void
    {
        $now = Carbon::now();
        $year = $year ?: (int) $now->year;
        $month = $month ?: (int) $now->month;

        Cache::forget(sprintf('dash:association:%d:%02d', $year, $month));
    }

    public function invalidateChurch(string $churchId, ?int $year = null): void
    {
        $year = $year ?: (int) Carbon::now()->year;

        Cache::forget(sprintf('dash:church:%s:%d', $churchId, $year));
    }
}
