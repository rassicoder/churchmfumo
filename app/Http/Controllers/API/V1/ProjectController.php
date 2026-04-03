<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ListProjectRequest;
use App\Http\Requests\Project\ProjectDashboardSummaryRequest;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projectService)
    {
    }

    public function index(ListProjectRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            $filters['church_id'] = $user->church_id;
        }
        $perPage = (int) ($filters['per_page'] ?? 15);
        unset($filters['per_page']);

        $projects = $this->projectService->list($filters, $perPage);

        return response()->json([
            'data' => $projects->items(),
            'meta' => [
                'current_page' => $projects->currentPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
                'last_page' => $projects->lastPage(),
            ],
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            $data['church_id'] = $user->church_id;
        }

        $project = $this->projectService->create($data);

        return response()->json([
            'message' => 'Project created successfully.',
            'data' => $project,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $project = $this->projectService->show($id);

        if (! $project) {
            return response()->json(['message' => 'Project not found.'], 404);
        }

        $user = request()->user();
        if ($user?->hasRole('Church Admin') && $project->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['data' => $project]);
    }

    public function update(UpdateProjectRequest $request, string $id): JsonResponse
    {
        $existing = $this->projectService->show($id);

        if (! $existing) {
            return response()->json(['message' => 'Project not found.'], 404);
        }

        $user = $request->user();
        if ($user?->hasRole('Church Admin') && $existing->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();
        if ($user?->hasRole('Church Admin')) {
            $data['church_id'] = $user->church_id;
        }

        $project = $this->projectService->update($id, $data);

        if (! $project) {
            return response()->json(['message' => 'Project not found.'], 404);
        }

        return response()->json([
            'message' => 'Project updated successfully.',
            'data' => $project,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $existing = $this->projectService->show($id);

        if (! $existing) {
            return response()->json(['message' => 'Project not found.'], 404);
        }

        $user = request()->user();
        if ($user?->hasRole('Church Admin') && $existing->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $deleted = $this->projectService->delete($id);

        if (! $deleted) {
            return response()->json(['message' => 'Project not found.'], 404);
        }

        return response()->json(['message' => 'Project deleted successfully.']);
    }

    public function dashboardSummary(ProjectDashboardSummaryRequest $request): JsonResponse
    {
        $summary = $this->projectService->dashboardSummary($request->validated());

        return response()->json(['data' => $summary]);
    }
}
