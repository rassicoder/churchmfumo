<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Leader\ListLeaderRequest;
use App\Http\Requests\Leader\StoreLeaderRequest;
use App\Http\Requests\Leader\UpdateLeaderRequest;
use App\Services\LeaderService;
use Illuminate\Http\JsonResponse;

class LeaderController extends Controller
{
    public function __construct(private readonly LeaderService $leaderService)
    {
    }

    public function index(ListLeaderRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            $filters['church_id'] = $user->church_id;
        }
        $perPage = (int) ($filters['per_page'] ?? 15);
        unset($filters['per_page']);

        $leaders = $this->leaderService->list($filters, $perPage);

        return response()->json([
            'data' => $leaders->items(),
            'meta' => [
                'current_page' => $leaders->currentPage(),
                'per_page' => $leaders->perPage(),
                'total' => $leaders->total(),
                'last_page' => $leaders->lastPage(),
            ],
        ]);
    }

    public function store(StoreLeaderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            $data['church_id'] = $user->church_id;
        }

        $leader = $this->leaderService->create($data);

        return response()->json([
            'message' => 'Leader created successfully.',
            'data' => $leader,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $leader = $this->leaderService->show($id);

        if (! $leader) {
            return response()->json([
                'message' => 'Leader not found.',
            ], 404);
        }

        $user = request()->user();
        if ($user?->hasRole('Church Admin') && $leader->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'data' => $leader,
        ]);
    }

    public function update(UpdateLeaderRequest $request, string $id): JsonResponse
    {
        $existing = $this->leaderService->show($id);

        if (! $existing) {
            return response()->json([
                'message' => 'Leader not found.',
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

        $leader = $this->leaderService->update($id, $data);

        if (! $leader) {
            return response()->json([
                'message' => 'Leader not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Leader updated successfully.',
            'data' => $leader,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $existing = $this->leaderService->show($id);

        if (! $existing) {
            return response()->json([
                'message' => 'Leader not found.',
            ], 404);
        }

        $user = request()->user();
        if ($user?->hasRole('Church Admin') && $existing->church_id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $deleted = $this->leaderService->delete($id);

        if (! $deleted) {
            return response()->json([
                'message' => 'Leader not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Leader deleted successfully.',
        ]);
    }

    public function profile(string $id): JsonResponse
    {
        $profile = $this->leaderService->profile($id);

        if (! $profile) {
            return response()->json([
                'message' => 'Leader not found.',
            ], 404);
        }

        return response()->json([
            'data' => $profile,
        ]);
    }
}
