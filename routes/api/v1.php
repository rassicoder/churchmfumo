<?php

use App\Http\Controllers\API\V1\Auth\AuthenticatedSessionController;
use App\Http\Controllers\API\V1\Auth\EmailVerificationController;
use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\PasswordResetController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\ActionItemController;
use App\Http\Controllers\API\V1\ChurchController;
use App\Http\Controllers\API\V1\DepartmentController;
use App\Http\Controllers\API\V1\BudgetController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\ExpenseController;
use App\Http\Controllers\API\V1\LeaderController;
use App\Http\Controllers\API\V1\MeetingController;
use App\Http\Controllers\API\V1\ProjectController;
use App\Http\Controllers\API\V1\ActivityLogController;
use App\Http\Controllers\API\V1\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
    Route::post('/reset-password', [PasswordResetController::class, 'reset']);

    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('/me', [AuthenticatedSessionController::class, 'me'])
            ->middleware('role.redirect');

        Route::post('/logout', [AuthenticatedSessionController::class, 'logout']);

        Route::get('/email/verification-status', [EmailVerificationController::class, 'status']);
        Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
            ->middleware('throttle:6,1');

        // Example protected route set (mobile/web API) requiring verified email.
        Route::middleware('verified.api')->group(function (): void {
            Route::get('/session/validated', fn () => response()->json(['message' => 'Session valid.']));
        });
    });
});

Route::prefix('management')
    ->middleware(['auth:sanctum', 'verified.api', 'role.required:Super Admin,Association Executive,Church Admin'])
    ->group(function (): void {
        Route::get('/dashboard', fn () => response()->json([
            'message' => 'Role-restricted management dashboard access granted.',
        ]));
    });

Route::prefix('churches')
    ->middleware(['auth:sanctum', 'verified.api'])
    ->group(function (): void {
        Route::get('/', [ChurchController::class, 'index'])
            ->middleware('permission:churches.view');
        Route::get('/{id}', [ChurchController::class, 'show'])
            ->middleware('permission:churches.view');
        Route::post('/', [ChurchController::class, 'store'])
            ->middleware('permission:churches.create');
        Route::put('/{id}', [ChurchController::class, 'update'])
            ->middleware('permission:churches.update');
        Route::delete('/{id}', [ChurchController::class, 'destroy'])
            ->middleware('permission:churches.delete');
    });

Route::prefix('leaders')
    ->middleware(['auth:sanctum', 'verified.api', 'role.required:Super Admin,Association Executive,Church Admin'])
    ->group(function (): void {
        Route::get('/', [LeaderController::class, 'index']);
        Route::post('/', [LeaderController::class, 'store']);
        Route::get('/{id}', [LeaderController::class, 'show']);
        Route::put('/{id}', [LeaderController::class, 'update']);
        Route::delete('/{id}', [LeaderController::class, 'destroy']);
        Route::get('/{id}/profile', [LeaderController::class, 'profile']);
    });

Route::prefix('departments')
    ->middleware(['auth:sanctum', 'verified.api', 'role.required:Super Admin,Association Executive,Church Admin,Department Leader'])
    ->group(function (): void {
        Route::get('/', [DepartmentController::class, 'index']);
        Route::post('/', [DepartmentController::class, 'store']);
        Route::get('/{id}', [DepartmentController::class, 'show']);
        Route::put('/{id}', [DepartmentController::class, 'update']);
        Route::delete('/{id}', [DepartmentController::class, 'destroy']);
    });

Route::prefix('meetings')
    ->middleware(['auth:sanctum', 'verified.api', 'role.required:Super Admin,Association Executive,Church Admin,Secretary,Department Leader'])
    ->group(function (): void {
        Route::get('/dashboard/summary', [MeetingController::class, 'dashboardSummary']);
        Route::get('/', [MeetingController::class, 'index']);
        Route::post('/', [MeetingController::class, 'store']);
        Route::get('/{id}', [MeetingController::class, 'show']);
        Route::put('/{id}', [MeetingController::class, 'update']);
        Route::delete('/{id}', [MeetingController::class, 'destroy']);

        Route::get('/{meetingId}/action-items', [ActionItemController::class, 'index']);
        Route::post('/{meetingId}/action-items', [ActionItemController::class, 'store']);
    });

Route::prefix('action-items')
    ->middleware(['auth:sanctum', 'verified.api', 'role.required:Super Admin,Association Executive,Church Admin,Secretary,Department Leader'])
    ->group(function (): void {
        Route::get('/', [ActionItemController::class, 'index']);
        Route::get('/{id}', [ActionItemController::class, 'show']);
        Route::put('/{id}', [ActionItemController::class, 'update']);
        Route::delete('/{id}', [ActionItemController::class, 'destroy']);
    });

Route::prefix('projects')
    ->middleware(['auth:sanctum', 'verified.api', 'permission:projects.view|projects.create|projects.update|projects.delete'])
    ->group(function (): void {
        Route::get('/dashboard/summary', [ProjectController::class, 'dashboardSummary'])
            ->middleware('permission:projects.view');
        Route::get('/', [ProjectController::class, 'index'])
            ->middleware('permission:projects.view');
        Route::post('/', [ProjectController::class, 'store'])
            ->middleware('permission:projects.create');
        Route::get('/{id}', [ProjectController::class, 'show'])
            ->middleware('permission:projects.view');
        Route::put('/{id}', [ProjectController::class, 'update'])
            ->middleware('permission:projects.update');
        Route::delete('/{id}', [ProjectController::class, 'destroy'])
            ->middleware('permission:projects.delete');
    });

Route::prefix('finance')
    ->middleware(['auth:sanctum', 'verified.api', 'permission:finance.view|finance.manage'])
    ->group(function (): void {
        Route::get('/summary', [BudgetController::class, 'financialSummary'])
            ->middleware('permission:finance.view');

        Route::get('/budgets', [BudgetController::class, 'index'])
            ->middleware('permission:finance.view');
        Route::post('/budgets', [BudgetController::class, 'store'])
            ->middleware('permission:finance.manage');
        Route::get('/budgets/{id}', [BudgetController::class, 'show'])
            ->middleware('permission:finance.view');
        Route::put('/budgets/{id}', [BudgetController::class, 'update'])
            ->middleware('permission:finance.manage');
        Route::delete('/budgets/{id}', [BudgetController::class, 'destroy'])
            ->middleware('permission:finance.manage');

        Route::get('/expenses', [ExpenseController::class, 'index'])
            ->middleware('permission:finance.view');
        Route::post('/expenses', [ExpenseController::class, 'store'])
            ->middleware('permission:finance.manage');
        Route::get('/expenses/{id}', [ExpenseController::class, 'show'])
            ->middleware('permission:finance.view');
        Route::put('/expenses/{id}', [ExpenseController::class, 'update'])
            ->middleware('permission:finance.manage');
        Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy'])
            ->middleware('permission:finance.manage');
    });

Route::prefix('reports')
    ->middleware(['auth:sanctum', 'verified.api', 'role.required:Super Admin,Association Executive,Church Admin'])
    ->group(function (): void {
        Route::get('/overview', [ReportController::class, 'overview']);
        Route::get('/church-growth', [ReportController::class, 'churchGrowth']);
        Route::get('/finance', [ReportController::class, 'finance']);
        Route::get('/export/csv', [ReportController::class, 'exportCsv']);
    });

Route::prefix('dashboards')
    ->middleware(['auth:sanctum', 'verified.api'])
    ->group(function (): void {
        Route::get('/summary', [DashboardController::class, 'summary'])
            ->middleware('role.required:Super Admin,Association Executive,Church Admin');
        Route::get('/association', [DashboardController::class, 'association'])
            ->middleware('role.required:Super Admin,Association Executive');
        Route::get('/church', [DashboardController::class, 'church'])
            ->middleware('role.required:Super Admin,Association Executive,Church Admin');
    });

Route::prefix('admin')
    ->middleware(['auth:sanctum', 'verified.api', 'role.required:Super Admin'])
    ->group(function (): void {
        Route::get('/activity-logs', [ActivityLogController::class, 'index']);
    });
