<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportsService
{
    private const CACHE_TTL_SECONDS = 300;

    public function overview(?string $churchId, Carbon $start, Carbon $end): array
    {
        $cacheKey = sprintf('reports:overview:%s:%s:%s', $churchId ?: 'all', $start->toDateString(), $end->toDateString());

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($churchId, $start, $end): array {
            $leadersQuery = DB::table('leaders')->whereNull('deleted_at');
            $meetingsQuery = DB::table('meetings')->whereNull('deleted_at');
            $projectsQuery = DB::table('projects')->whereNull('deleted_at');
            $churchesQuery = DB::table('churches')->whereNull('deleted_at');

            if ($churchId) {
                $leadersQuery->where('church_id', $churchId);
                $meetingsQuery->where('church_id', $churchId);
                $projectsQuery->where('church_id', $churchId);
            }

            $leadersTotal = $leadersQuery->count();
            $meetingsTotal = $meetingsQuery->whereBetween('meeting_date', [$start->toDateString(), $end->toDateString()])->count();
            $projectsTotal = $projectsQuery->count();
            $churchesTotal = $churchId ? 1 : $churchesQuery->count();

            $meetingFrequency = $this->monthlyCounts('meetings', 'meeting_date', $start, $end, $churchId);
            $leaderGrowth = $this->monthlyCounts('leaders', 'created_at', $start, $end, $churchId);

            return [
                'totals' => [
                    'leaders' => (int) $leadersTotal,
                    'meetings' => (int) $meetingsTotal,
                    'projects' => (int) $projectsTotal,
                    'churches' => (int) $churchesTotal,
                ],
                'leader_growth' => $leaderGrowth,
                'meeting_frequency' => $meetingFrequency,
            ];
        });
    }

    public function leaderGrowth(?string $churchId, Carbon $start, Carbon $end): array
    {
        return $this->monthlyCounts('leaders', 'created_at', $start, $end, $churchId);
    }

    public function churchGrowth(Carbon $start, Carbon $end): array
    {
        return $this->monthlyCounts('churches', 'created_at', $start, $end, null);
    }

    public function finance(?string $churchId, Carbon $start, Carbon $end): array
    {
        $cacheKey = sprintf('reports:finance:%s:%s:%s', $churchId ?: 'all', $start->toDateString(), $end->toDateString());

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($churchId, $start, $end): array {
            $labels = $this->buildMonthLabels($start, $end);
            $expenses = $this->monthlySums('expenses', 'date', 'amount', $start, $end, $churchId);

            $budgetTotal = DB::table('budgets')
                ->when($churchId, fn ($q) => $q->where('church_id', $churchId))
                ->whereNull('deleted_at')
                ->whereIn('year', array_unique(array_map(fn ($label) => (int) substr($label, 0, 4), $labels)))
                ->sum('allocated_amount');

            $monthsCount = max(count($labels), 1);
            $monthlyBudget = array_fill(0, $monthsCount, (float) $budgetTotal / $monthsCount);

            return [
                'labels' => array_map(fn ($label) => Carbon::createFromFormat('Y-m', $label)->format('M Y'), $labels),
                'budgets' => $monthlyBudget,
                'expenses' => $expenses,
            ];
        });
    }

    public function buildDateRange(?string $range, ?string $startDate, ?string $endDate): array
    {
        if ($startDate && $endDate) {
            return [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()];
        }

        $today = Carbon::today();
        if ($range === '7d') {
            return [$today->copy()->subDays(6)->startOfDay(), $today->copy()->endOfDay()];
        }
        if ($range === '30d') {
            return [$today->copy()->subDays(29)->startOfDay(), $today->copy()->endOfDay()];
        }

        return [$today->copy()->startOfYear(), $today->copy()->endOfYear()];
    }

    private function monthlyCounts(string $table, string $dateColumn, Carbon $start, Carbon $end, ?string $churchId): array
    {
        $labels = $this->buildMonthLabels($start, $end);
        $rows = DB::table($table)
            ->selectRaw("DATE_FORMAT({$dateColumn}, '%Y-%m') as ym, COUNT(*) as total")
            ->whereNull('deleted_at')
            ->when($churchId, fn ($q) => $q->where('church_id', $churchId))
            ->whereBetween($dateColumn, [$start->toDateString(), $end->toDateString()])
            ->groupBy('ym')
            ->pluck('total', 'ym')
            ->all();

        $data = [];
        foreach ($labels as $label) {
            $data[] = (int) ($rows[$label] ?? 0);
        }

        return [
            'labels' => array_map(fn ($label) => Carbon::createFromFormat('Y-m', $label)->format('M Y'), $labels),
            'data' => $data,
        ];
    }

    private function monthlySums(string $table, string $dateColumn, string $amountColumn, Carbon $start, Carbon $end, ?string $churchId): array
    {
        $labels = $this->buildMonthLabels($start, $end);
        $query = DB::table($table)
            ->selectRaw("DATE_FORMAT({$dateColumn}, '%Y-%m') as ym, SUM({$amountColumn}) as total")
            ->whereNull('deleted_at')
            ->whereBetween($dateColumn, [$start->toDateString(), $end->toDateString()]);

        if ($churchId) {
            if ($table === 'expenses') {
                $query->join('projects', 'projects.id', '=', 'expenses.project_id')
                    ->whereNull('projects.deleted_at')
                    ->where('projects.church_id', $churchId);
            } else {
                $query->where('church_id', $churchId);
            }
        }

        $rows = $query->groupBy('ym')->pluck('total', 'ym')->all();

        $data = [];
        foreach ($labels as $label) {
            $data[] = (float) ($rows[$label] ?? 0);
        }

        return $data;
    }

    private function buildMonthLabels(Carbon $start, Carbon $end): array
    {
        $periodStart = $start->copy()->startOfMonth();
        $periodEnd = $end->copy()->startOfMonth();
        $labels = [];

        while ($periodStart <= $periodEnd) {
            $labels[] = $periodStart->format('Y-m');
            $periodStart->addMonth();
        }

        return $labels;
    }
}
