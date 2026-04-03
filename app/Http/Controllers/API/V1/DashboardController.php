<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AssociationDashboardRequest;
use App\Http\Requests\Dashboard\ChurchDashboardRequest;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService)
    {
    }

    public function association(AssociationDashboardRequest $request): JsonResponse
    {
        $year = $request->integer('year');
        $month = $request->integer('month');

        $data = $this->dashboardService->associationSummary($year ?: null, $month ?: null);

        return response()->json(['data' => $data]);
    }

    public function church(ChurchDashboardRequest $request): JsonResponse
    {
        $data = $this->dashboardService->churchSummary(
            $request->string('church_id')->toString(),
            $request->integer('year') ?: null
        );

        return response()->json(['data' => $data]);
    }

    public function summary(AssociationDashboardRequest $request): JsonResponse
    {
        $year = $request->integer('year');
        $month = $request->integer('month');
        $user = $request->user();

        if ($user?->hasRole('Church Admin') && !$user->church_id) {
            return response()->json(['message' => 'Church Admin must be assigned to a church.'], 422);
        }

        $data = $this->dashboardService->roleBasedSummary($user, $year ?: null, $month ?: null);

        return response()->json(['data' => $data]);
    }
}
