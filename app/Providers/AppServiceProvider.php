<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Sanctum + Custom Token Model
use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;

// Repositories
use App\Repositories\Contracts\ChurchRepositoryInterface;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Repositories\Contracts\ActionItemRepositoryInterface;
use App\Repositories\Contracts\LeaderRepositoryInterface;
use App\Repositories\Contracts\MeetingRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

use App\Repositories\Eloquent\ActionItemRepository;
use App\Repositories\Eloquent\ChurchRepository;
use App\Repositories\Eloquent\DepartmentRepository;
use App\Repositories\Eloquent\LeaderRepository;
use App\Repositories\Eloquent\MeetingRepository;
use App\Repositories\Eloquent\ProjectRepository;
use App\Repositories\Eloquent\BudgetRepository;
use App\Repositories\Eloquent\ExpenseRepository;
use App\Repositories\Eloquent\ActivityLogRepository;
use App\Repositories\Eloquent\UserRepository;

// Services
use App\Services\ActionItemService;
use App\Services\AuthService;
use App\Services\ChurchService;
use App\Services\DepartmentService;
use App\Services\LeaderService;
use App\Services\MeetingService;
use App\Services\ProjectService;
use App\Services\BudgetService;
use App\Services\ExpenseService;
use App\Services\DashboardService;
use App\Services\ReportsService;
use App\Services\DashboardCacheService;
use App\Services\ActivityLogService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Prevent Sanctum from loading its default migration (we have a custom one).
        Sanctum::ignoreMigrations();

        // Bind repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ChurchRepositoryInterface::class, ChurchRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(LeaderRepositoryInterface::class, LeaderRepository::class);
        $this->app->bind(MeetingRepositoryInterface::class, MeetingRepository::class);
        $this->app->bind(ActionItemRepositoryInterface::class, ActionItemRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(BudgetRepositoryInterface::class, BudgetRepository::class);
        $this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);

        // Bind services
        $this->app->singleton(AuthService::class);
        $this->app->singleton(ChurchService::class);
        $this->app->singleton(DepartmentService::class);
        $this->app->singleton(LeaderService::class);
        $this->app->singleton(MeetingService::class);
        $this->app->singleton(ActionItemService::class);
        $this->app->singleton(ProjectService::class);
        $this->app->singleton(BudgetService::class);
        $this->app->singleton(ExpenseService::class);
        $this->app->singleton(DashboardService::class);
        $this->app->singleton(ReportsService::class);
        $this->app->singleton(DashboardCacheService::class);
        $this->app->singleton(ActivityLogService::class);
    }

    public function boot(): void
    {
        // Use UUID-based token model
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
