<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Meeting\DashboardSummaryRequest;
use App\Http\Requests\Meeting\ListMeetingRequest;
use App\Http\Requests\Meeting\StoreMeetingRequest;
use App\Http\Requests\Meeting\UpdateMeetingRequest;
use App\Services\MeetingService;
use Illuminate\Http\JsonResponse;

class MeetingController extends Controller
{
    public function __construct(private readonly MeetingService $meetingService)
    {
    }

    public function index(ListMeetingRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            $filters['church_id'] = $user->church_id;
        }
        $perPage = (int) ($filters['per_page'] ?? 15);
        unset($filters['per_page']);

        $meetings = $this->meetingService->list($filters, $perPage);

        return response()->json([
            'data' => $meetings->items(),
            'meta' => [
                'current_page' => $meetings->currentPage(),
                'per_page' => $meetings->perPage(),
                'total' => $meetings->total(),
                'last_page' => $meetings->lastPage(),
            ],
        ]);
    }

    public function store(StoreMeetingRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            $data['church_id'] = $user->church_id;
        }

        $meeting = $this->meetingService->create($data);

        return response()->json([
            'message' => 'Meeting created successfully.',
            'data' => $meeting,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $meeting = $this->meetingService->show($id);

        if (! $meeting) {
            return response()->json(['message' => 'Meeting not found.'], 404);
        }

        $user = request()->user();
        if ($user?->hasRole('Church Admin') && $meeting->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['data' => $meeting]);
    }

    public function update(UpdateMeetingRequest $request, string $id): JsonResponse
    {
        $existing = $this->meetingService->show($id);

        if (! $existing) {
            return response()->json(['message' => 'Meeting not found.'], 404);
        }

        $user = $request->user();
        if ($user?->hasRole('Church Admin') && $existing->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();
        if ($user?->hasRole('Church Admin')) {
            $data['church_id'] = $user->church_id;
        }

        $meeting = $this->meetingService->update($id, $data);

        if (! $meeting) {
            return response()->json(['message' => 'Meeting not found.'], 404);
        }

        return response()->json([
            'message' => 'Meeting updated successfully.',
            'data' => $meeting,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $existing = $this->meetingService->show($id);

        if (! $existing) {
            return response()->json(['message' => 'Meeting not found.'], 404);
        }

        $user = request()->user();
        if ($user?->hasRole('Church Admin') && $existing->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $deleted = $this->meetingService->delete($id);

        if (! $deleted) {
            return response()->json(['message' => 'Meeting not found.'], 404);
        }

        return response()->json(['message' => 'Meeting deleted successfully.']);
    }

    public function dashboardSummary(DashboardSummaryRequest $request): JsonResponse
    {
        $summary = $this->meetingService->dashboardSummary($request->validated());

        return response()->json(['data' => $summary]);
    }
}
