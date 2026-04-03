<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardService
{
    private const CACHE_TTL_SECONDS = 300;

    public function associationSummary(?int $year = null, ?int $month = null): array
    {
        $now = Carbon::now();
        $year = $year ?: (int) $now->year;
        $month = $month ?: (int) $now->month;

        $cacheKey = sprintf('dash:association:%d:%02d', $year, $month);

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($year, $month): array {
            $today = Carbon::today()->toDateString();
            $monthStart = Carbon::create($year, $month, 1)->startOfDay();
            $monthEnd = (clone $monthStart)->endOfMonth();

            $totalChurches = DB::table('churches')->whereNull('deleted_at')->count();
            $totalLeaders = DB::table('leaders')->whereNull('deleted_at')->count();
            $totalProjects = DB::table('projects')
                ->whereNull('deleted_at')
                ->count();

            $activeProjects = DB::table('projects')
                ->whereNull('deleted_at')
                ->where('status', 'active')
                ->count();

            $totalMeetings = DB::table('meetings')
                ->whereNull('deleted_at')
                ->count();

            $overdueActions = DB::table('action_items')
                ->whereNull('deleted_at')
                ->whereDate('deadline', '<', $today)
                ->whereIn('status', config('meeting.open_action_statuses', ['pending', 'in_progress']))
                ->count();

            $allocatedYearTotal = DB::table('budgets')
                ->whereNull('deleted_at')
                ->where('year', $year)
                ->sum('allocated_amount');

            $spentMonthTotal = DB::table('expenses')
                ->whereNull('deleted_at')
                ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->sum('amount');

            $spentYearToDate = DB::table('expenses')
                ->whereNull('deleted_at')
                ->whereBetween('date', [$monthStart->copy()->startOfYear()->toDateString(), $monthEnd->toDateString()])
                ->sum('amount');

            return [
                'totals' => [
                    'churches' => (int) $totalChurches,
                    'leaders' => (int) $totalLeaders,
                    'meetings' => (int) $totalMeetings,
                    'projects' => (int) $totalProjects,
                    'active_projects' => (int) $activeProjects,
                    'overdue_actions' => (int) $overdueActions,
                ],
                'financial' => [
                    'year' => $year,
                    'month' => $month,
                    'allocated_year_total' => (float) $allocatedYearTotal,
                    'spent_month_total' => (float) $spentMonthTotal,
                    'spent_year_to_date' => (float) $spentYearToDate,
                    'variance_year_to_date' => (float) ($allocatedYearTotal - $spentYearToDate),
                ],
            ];
        });
    }

    public function roleBasedSummary(User $user, ?int $year = null, ?int $month = null): array
    {
        if ($user->hasAnyRole(['Super Admin', 'Association Executive'])) {
            return $this->associationSummary($year, $month);
        }

        if ($user->hasRole('Church Admin')) {
            return $this->churchAdminSummary($user->church_id, $year);
        }

        return $this->associationSummary($year, $month);
    }

    public function churchAdminSummary(?string $churchId, ?int $year = null): array
    {
        if (!$churchId) {
            return [
                'totals' => [
                    'churches' => 0,
                    'leaders' => 0,
                    'meetings' => 0,
                    'projects' => 0,
                ],
            ];
        }

        $year = $year ?: (int) Carbon::now()->year;
        $cacheKey = sprintf('dash:church-admin:%s:%d', $churchId, $year);

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($churchId, $year): array {
            $totalLeaders = DB::table('leaders')
                ->whereNull('deleted_at')
                ->where('church_id', $churchId)
                ->count();

            $totalMeetings = DB::table('meetings')
                ->whereNull('deleted_at')
                ->where('church_id', $churchId)
                ->count();

            $totalProjects = DB::table('projects')
                ->whereNull('deleted_at')
                ->where('church_id', $churchId)
                ->count();

            return [
                'totals' => [
                    'churches' => 1,
                    'leaders' => (int) $totalLeaders,
                    'meetings' => (int) $totalMeetings,
                    'projects' => (int) $totalProjects,
                ],
            ];
        });
    }

    public function churchSummary(string $churchId, ?int $year = null): array
    {
        $year = $year ?: (int) Carbon::now()->year;
        $cacheKey = sprintf('dash:church:%s:%d', $churchId, $year);

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($churchId, $year): array {
            $departmentsCount = DB::table('departments')
                ->whereNull('deleted_at')
                ->where('church_id', $churchId)
                ->count();

            $ongoingProjects = DB::table('projects')
                ->whereNull('deleted_at')
                ->where('church_id', $churchId)
                ->where('status', 'active')
                ->count();

            $pendingActions = DB::table('action_items')
                ->join('meetings', 'meetings.id', '=', 'action_items.meeting_id')
                ->whereNull('action_items.deleted_at')
                ->whereNull('meetings.deleted_at')
                ->where('meetings.church_id', $churchId)
                ->whereIn('action_items.status', config('meeting.open_action_statuses', ['pending', 'in_progress']))
                ->count();

            $allocated = DB::table('budgets')
                ->whereNull('deleted_at')
                ->where('church_id', $churchId)
                ->where('year', $year)
                ->sum('allocated_amount');

            $spent = DB::table('expenses')
                ->join('projects', 'projects.id', '=', 'expenses.project_id')
                ->whereNull('expenses.deleted_at')
                ->whereNull('projects.deleted_at')
                ->where('projects.church_id', $churchId)
                ->whereYear('expenses.date', $year)
                ->sum('expenses.amount');

            return [
                'counts' => [
                    'departments' => (int) $departmentsCount,
                    'ongoing_projects' => (int) $ongoingProjects,
                    'pending_actions' => (int) $pendingActions,
                ],
                'budget' => [
                    'year' => $year,
                    'allocated' => (float) $allocated,
                    'spent' => (float) $spent,
                    'variance' => (float) ($allocated - $spent),
                ],
            ];
        });
    }
}
