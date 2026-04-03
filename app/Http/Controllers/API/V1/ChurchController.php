<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Church\ListChurchRequest;
use App\Http\Requests\Church\StoreChurchRequest;
use App\Http\Requests\Church\UpdateChurchRequest;
use App\Services\ChurchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ChurchController extends Controller
{
    public function __construct(private readonly ChurchService $churchService)
    {
    }

    public function index(ListChurchRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $user = $request->user();
        if ($user?->hasRole('Church Admin')) {
            $filters['id'] = $user->church_id;
        }
        $perPage = (int) ($filters['per_page'] ?? 15);
        unset($filters['per_page']);

        $churches = $this->churchService->list($filters, $perPage);

        return response()->json([
            'data' => $churches->items(),
            'meta' => [
                'current_page' => $churches->currentPage(),
                'per_page' => $churches->perPage(),
                'total' => $churches->total(),
                'last_page' => $churches->lastPage(),
            ],
        ]);
    }

    public function store(StoreChurchRequest $request): JsonResponse
    {
        if (! $request->user()?->hasRole('Super Admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();

        $adminData = [
            'name' => $data['admin_name'],
            'email' => $data['admin_email'],
            'password' => Hash::make($data['admin_password']),
            'email_verified_at' => now(),
        ];

        unset($data['admin_name'], $data['admin_email'], $data['admin_password']);

        try {
            $result = DB::transaction(function () use ($data, $adminData) {
                $church = $this->churchService->create($data);

                /** @var User $user */
                $user = User::create(array_merge($adminData, [
                    'church_id' => $church->id,
                ]));
                $user->assignRole('Church Admin');

                return [
                    'church' => $church,
                    'admin' => $user->only(['id', 'name', 'email', 'church_id']),
                ];
            });
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to create church admin.',
            ], 500);
        }

        return response()->json([
            'message' => 'Church created successfully.',
            'data' => $result,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $church = $this->churchService->show($id);

        if (! $church) {
            return response()->json([
                'message' => 'Church not found.',
            ], 404);
        }

        $user = request()->user();
        if ($user?->hasRole('Church Admin') && $church->id !== $user->church_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'data' => $church,
        ]);
    }

    public function update(UpdateChurchRequest $request, string $id): JsonResponse
    {
        if (! $request->user()?->hasRole('Super Admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $church = $this->churchService->update($id, $request->validated());

        if (! $church) {
            return response()->json([
                'message' => 'Church not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Church updated successfully.',
            'data' => $church,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        if (! request()->user()?->hasRole('Super Admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $deleted = $this->churchService->delete($id);

        if (! $deleted) {
            return response()->json([
                'message' => 'Church not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Church deleted successfully.',
        ]);
    }
}
