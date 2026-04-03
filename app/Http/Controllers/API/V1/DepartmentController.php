<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\ListDepartmentRequest;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Services\DepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    public function __construct(private readonly DepartmentService $departmentService)
    {
    }

    public function index(ListDepartmentRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            $filters['church_id'] = $user->church_id;
        }
        $perPage = (int) ($filters['per_page'] ?? 15);
        unset($filters['per_page']);

        $departments = $this->departmentService->list($filters, $perPage);

        return response()->json([
            'data' => $departments->items(),
            'meta' => [
                'current_page' => $departments->currentPage(),
                'per_page' => $departments->perPage(),
                'total' => $departments->total(),
                'last_page' => $departments->lastPage(),
            ],
        ]);
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            $data['church_id'] = $user->church_id;
        }

        $department = $this->departmentService->create($data);

        return response()->json([
            'message' => 'Department created successfully.',
            'data' => $department,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $department = $this->departmentService->show($id);

        if (! $department) {
            return response()->json([
                'message' => 'Department not found.',
            ], 404);
        }

        $user = request()->user();
        if ($user?->hasRole('Church Admin') && $department->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'data' => $department,
        ]);
    }

    public function update(UpdateDepartmentRequest $request, string $id): JsonResponse
    {
        $existing = $this->departmentService->show($id);

        if (! $existing) {
            return response()->json([
                'message' => 'Department not found.',
            ], 404);
        }

        $user = $request->user();
        if ($user?->hasRole('Church Admin') && $existing->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();
        if ($user?->hasRole('Church Admin')) {
            $data['church_id'] = $user->church_id;
        }

        $department = $this->departmentService->update($id, $data);

        if (! $department) {
            return response()->json([
                'message' => 'Department not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Department updated successfully.',
            'data' => $department,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $existing = $this->departmentService->show($id);

        if (! $existing) {
            return response()->json([
                'message' => 'Department not found.',
            ], 404);
        }

        $user = request()->user();
        if ($user?->hasRole('Church Admin') && $existing->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $deleted = $this->departmentService->delete($id);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Cannot delete department.',
                'errors' => $exception->errors(),
            ], 422);
        }

        if (! $deleted) {
            return response()->json([
                'message' => 'Department not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Department deleted successfully.',
        ]);
    }
}
